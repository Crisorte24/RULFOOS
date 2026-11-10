<?php
// Paso 1 del pago: el navegador manda el carrito, el servidor revisa stock y precios
// en la base de datos y crea la orden en PayPal por el total correcto.
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
$items = $entrada['items'] ?? null;

$carrito = validarCarrito($conn, $items);
if ($carrito['errores']) {
    responder(409, ['error' => implode("\n", $carrito['errores'])]);
}

try {
    $ordenId = paypalCrearOrden($carrito['total']);
} catch (RuntimeException $ex) {
    error_log('PayPal crear orden: ' . $ex->getMessage());
    responder(502, ['error' => 'No se pudo iniciar el pago con PayPal. Intenta de nuevo.']);
}

// Se guarda en la sesión QUÉ se va a comprar: al cobrar se usa esto, no lo que mande el navegador.
$_SESSION['paypal_ordenes'][$ordenId] = ['items' => $items, 'total' => $carrito['total']];
$_SESSION['paypal_ordenes'] = array_slice($_SESSION['paypal_ordenes'], -5, null, true); // solo las últimas 5

responder(200, ['id' => $ordenId, 'total' => $carrito['total']]);
