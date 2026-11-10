<?php
// Ventas registradas desde la tienda (pagos con PayPal) e inventario bajo.
require 'auth.php';
require 'conexion.php';

$ventas = $conn->query('SELECT * FROM ventas ORDER BY fecha DESC, id DESC LIMIT 200')->fetch_all(MYSQLI_ASSOC);

// Detalle de cada venta, agrupado por venta
$detalles = [];
if ($ventas) {
    $ids = implode(',', array_map('intval', array_column($ventas, 'id')));
    foreach ($conn->query("SELECT * FROM ventas_detalle WHERE venta_id IN ($ids) ORDER BY id")->fetch_all(MYSQLI_ASSOC) as $d) {
        $detalles[$d['venta_id']][] = $d;
    }
}

$resumen = $conn->query(
    'SELECT COUNT(*) AS num, COALESCE(SUM(total), 0) AS total FROM ventas
     WHERE fecha >= DATE_FORMAT(CURDATE(), "%Y-%m-01")'
)->fetch_assoc();

$pocoStock = $conn->query('SELECT id, nombre, cantidad FROM productos WHERE cantidad <= 3 ORDER BY cantidad, nombre')->fetch_all(MYSQLI_ASSOC);

function dinero($n)
{
    return '$' . number_format((float) $n, 2);
}

$titulo = 'Ventas';
$seccion = 'ventas';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Ventas e inventario</h1>
                <p>Este mes: <strong><?php echo (int) $resumen['num']; ?> venta(s)</strong> por <strong><?php echo dinero($resumen['total']); ?></strong></p>
            </div>
        </div>

        <section class="tarjeta admin-seccion">
            <h2>Inventario bajo</h2>
            <?php if (!$pocoStock): ?>
                <p class="vacio">Todos los productos tienen más de 3 piezas.</p>
            <?php else: ?>
                <div class="tabla-scroll">
                    <table class="tabla">
                        <thead><tr><th>Producto</th><th>Inventario</th></tr></thead>
                        <tbody>
                        <?php foreach ($pocoStock as $p): ?>
                            <tr>
                                <td><?php echo e($p['nombre']); ?></td>
                                <td>
                                    <?php if ($p['cantidad'] < 1): ?>
                                        <span class="estado peligro">Agotado</span>
                                    <?php else: ?>
                                        <span class="estado alerta"><?php echo (int) $p['cantidad']; ?> piezas</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-acciones separado">
                    <a href="admin.php" class="btn btn-suave btn-chico">Reabastecer en Productos</a>
                </div>
            <?php endif; ?>
        </section>

        <section class="tarjeta admin-seccion">
            <h2>Últimas ventas</h2>
            <?php if (!$ventas): ?>
                <p class="vacio">Todavía no hay ventas. Aparecerán aquí cuando alguien pague con PayPal.</p>
            <?php else: ?>
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr><th>Fecha</th><th>Folio PayPal</th><th>Artículos</th><th class="num">Total</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="sin-salto"><?php echo e(date('d/m/Y H:i', strtotime($v['fecha']))); ?></td>
                            <td><code><?php echo e($v['referencia_pago']); ?></code></td>
                            <td>
                                <?php foreach ($detalles[$v['id']] ?? [] as $d): ?>
                                    <?php echo (int) $d['cantidad']; ?> × <?php echo e($d['nombre']); ?>
                                    <?php echo $d['tipo'] === 'promo' ? '<span class="estado ok">promoción</span>' : ''; ?>
                                    <span class="ayuda">— <?php echo dinero($d['precio'] * $d['cantidad']); ?></span><br>
                                <?php endforeach; ?>
                            </td>
                            <td class="num"><strong><?php echo dinero($v['total']); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>
<?php
require 'admin_pie.php';
