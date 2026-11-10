<?php
// Lógica del inventario y las ventas.
// La usan paypal_crear_orden.php y paypal_capturar.php.
//
// Regla de oro: los precios y el stock SIEMPRE se leen de la base de datos.
// Del navegador solo se acepta QUÉ quiere comprar el cliente y CUÁNTO.

// $items viene del navegador: [['clave' => 'producto-6', 'cantidad' => 2], ['clave' => 'promo-1', 'cantidad' => 1]]
// Regresa ['lineas' => [...], 'total' => 123.45, 'errores' => ['...']].
// Con $bloquear = true reserva las filas (SELECT ... FOR UPDATE) hasta que termine la transacción.
function validarCarrito($conn, $items, $bloquear = false)
{
    if (!is_array($items) || count($items) === 0) {
        return ['lineas' => [], 'total' => 0.0, 'errores' => ['Tu carrito está vacío.']];
    }
    if (count($items) > 100) {
        return ['lineas' => [], 'total' => 0.0, 'errores' => ['El carrito tiene demasiados artículos.']];
    }

    // Juntar por artículo (por si la misma clave viene repetida)
    $pedido = [];
    foreach ($items as $item) {
        $clave    = is_array($item) ? (string) ($item['clave'] ?? '') : '';
        $cantidad = is_array($item) ? (int) ($item['cantidad'] ?? 0) : 0;
        if (!preg_match('/^(producto|promo)-(\d+)$/', $clave, $m) || $cantidad < 1 || $cantidad > 999) {
            return ['lineas' => [], 'total' => 0.0, 'errores' => ['Hay un artículo inválido en el carrito.']];
        }
        if (!isset($pedido[$clave])) {
            $pedido[$clave] = ['tipo' => $m[1], 'id' => (int) $m[2], 'cantidad' => 0];
        }
        $pedido[$clave]['cantidad'] += $cantidad;
    }

    $bloqueo = $bloquear ? ' FOR UPDATE' : '';
    // precio1 = precio normal (se muestra tachado), precio2 = precio de venta (el que se cobra)
    $consultaProducto = $conn->prepare('SELECT nombre, precio2 AS precio, cantidad FROM productos WHERE id = ?' . $bloqueo);
    $consultaPromo    = $conn->prepare('SELECT nombre_promocion AS nombre, precio_descuento AS precio FROM promociones WHERE id_promocion = ?' . $bloqueo);

    $lineas = [];
    $errores = [];
    $total = 0.0;

    foreach ($pedido as $clave => $p) {
        $consulta = $p['tipo'] === 'producto' ? $consultaProducto : $consultaPromo;
        $consulta->bind_param('i', $p['id']);
        $consulta->execute();
        $fila = $consulta->get_result()->fetch_assoc();

        if (!$fila) {
            $errores[] = 'Un artículo de tu carrito ya no está a la venta.';
            continue;
        }
        if ($p['tipo'] === 'producto' && (int) $fila['cantidad'] < $p['cantidad']) {
            $errores[] = (int) $fila['cantidad'] > 0
                ? 'Solo quedan ' . (int) $fila['cantidad'] . ' pieza(s) de ' . $fila['nombre'] . '.'
                : $fila['nombre'] . ' está agotado.';
            continue;
        }

        $precio = round((float) $fila['precio'], 2);
        $lineas[] = [
            'clave'    => $clave,
            'tipo'     => $p['tipo'],
            'id'       => $p['id'],
            'nombre'   => $fila['nombre'],
            'precio'   => $precio,
            'cantidad' => $p['cantidad'],
        ];
        $total += $precio * $p['cantidad'];
    }

    return ['lineas' => $lineas, 'total' => round($total, 2), 'errores' => $errores];
}

// Registra una venta completa en UNA sola transacción:
//   1. Reserva el stock y lo vuelve a revisar (nadie más puede comprarlo mientras tanto).
//   2. Guarda la venta y descuenta las piezas.
//   3. Cobra llamando a $cobrar($total), que debe regresar el monto cobrado o lanzar una excepción.
//   4. Solo si el cobro salió bien y el monto coincide, confirma todo (commit).
// Si cualquier paso falla, se deshace todo (rollback): no se descuenta nada.
function registrarVenta($conn, $items, $referencia, callable $cobrar)
{
    $conn->begin_transaction();
    try {
        $existe = $conn->prepare('SELECT id FROM ventas WHERE referencia_pago = ?');
        $existe->bind_param('s', $referencia);
        $existe->execute();
        if ($existe->get_result()->num_rows > 0) {
            throw new RuntimeException('Este pago ya se había registrado.');
        }

        $carrito = validarCarrito($conn, $items, true);
        if ($carrito['errores']) {
            throw new RuntimeException(implode(' ', $carrito['errores']));
        }
        $total = $carrito['total'];

        $venta = $conn->prepare('INSERT INTO ventas (referencia_pago, total) VALUES (?, ?)');
        $venta->bind_param('sd', $referencia, $total);
        $venta->execute();
        $ventaId = $conn->insert_id;

        $detalle = $conn->prepare(
            'INSERT INTO ventas_detalle (venta_id, tipo, item_id, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $descontar = $conn->prepare('UPDATE productos SET cantidad = cantidad - ? WHERE id = ? AND cantidad >= ?');

        foreach ($carrito['lineas'] as $l) {
            $detalle->bind_param('isisdi', $ventaId, $l['tipo'], $l['id'], $l['nombre'], $l['precio'], $l['cantidad']);
            $detalle->execute();

            if ($l['tipo'] === 'producto') {
                $descontar->bind_param('iii', $l['cantidad'], $l['id'], $l['cantidad']);
                $descontar->execute();
                if ($descontar->affected_rows !== 1) {
                    throw new RuntimeException('No hay suficientes piezas de ' . $l['nombre'] . '.');
                }
            }
        }

        // El cobro va al final: si algo de arriba falló, nunca se le cobra al cliente.
        $cobrado = (float) $cobrar($total);
        if (abs($cobrado - $total) > 0.009) {
            throw new RuntimeException('El monto cobrado no coincide con el total de la compra.');
        }

        $conn->commit();
        return ['venta_id' => $ventaId, 'total' => $total];
    } catch (Throwable $ex) {
        $conn->rollback();
        throw $ex;
    }
}
