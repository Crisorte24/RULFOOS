<?php
// Paso 2 del pago: el cliente ya aprobó en PayPal. Aquí se reserva el stock,
// se registra la venta, se cobra y se descuentan las piezas, todo junto (ver inventario.php).
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'conexion.php';
require 'inventario.php';
require 'paypal.php';

function responder($codigo, $datos)
{
    http_response_code($codigo);
    echo json_encode($datos);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, ['error' => 'Método no permitido.']);
}
if (!paypalConfigurado()) {
    responder(503, ['error' => 'El pago con PayPal todavía no está configurado.']);
}

$entrada = json_decode(file_get_contents('php://input'), true);
$ordenId = (string) ($entrada['orderID'] ?? '');

if (!preg_match('/^[A-Z0-9-]{5,64}$/', $ordenId) || !isset($_SESSION['paypal_ordenes'][$ordenId])) {
    responder(400, ['error' => 'No se encontró esta orden de pago. Vuelve a intentarlo.']);
}
$orden = $_SESSION['paypal_ordenes'][$ordenId];

$cobrado = false;
try {
    $venta = registrarVenta($conn, $orden['items'], $ordenId, function ($total) use ($ordenId, $orden, &$cobrado) {
        if (abs($total - $orden['total']) > 0.009) {
            throw new RuntimeException('Los precios cambiaron mientras pagabas. Revisa tu carrito y vuelve a intentarlo.');
        }
        $monto = paypalCapturar($ordenId);
        $cobrado = true;
        return $monto;
    });
} catch (Throwable $ex) {
    if ($cobrado) {
        // Muy raro: PayPal cobró pero no se pudo guardar la venta. Queda en el log de PHP para devolver el dinero.
        error_log("URGENTE: PayPal cobró la orden $ordenId pero la venta no se registró: " . $ex->getMessage());
        responder(500, ['error' => 'Tu pago se recibió pero hubo un problema al registrarlo. Contáctanos con este folio: ' . $ordenId]);
    }
    error_log("Venta $ordenId no registrada: " . $ex->getMessage());
    $mensaje = $ex instanceof mysqli_sql_exception
        ? 'Ocurrió un error al registrar tu compra. No se te cobró nada.'
        : $ex->getMessage() . ' No se te cobró nada.';
    responder(409, ['error' => $mensaje]);
}

unset($_SESSION['paypal_ordenes'][$ordenId]);
responder(200, ['ok' => true, 'venta' => $venta['venta_id'], 'total' => $venta['total']]);
