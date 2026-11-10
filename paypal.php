<?php
// Comunicación con PayPal desde el servidor: crear la orden y cobrarla.
// Las llaves van en config.php (NO se sube a Git). Copia config.example.php como config.php.
if (is_file(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
defined('PAYPAL_CLIENT_ID') || define('PAYPAL_CLIENT_ID', '');
defined('PAYPAL_SECRET')    || define('PAYPAL_SECRET', '');
defined('PAYPAL_MODO')      || define('PAYPAL_MODO', 'sandbox'); // 'sandbox' = pruebas, 'live' = dinero real
defined('PAYPAL_URL_BASE')  || define('PAYPAL_URL_BASE',
    PAYPAL_MODO === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com');

const PAYPAL_MONEDA = 'MXN';

function paypalConfigurado()
{
    return PAYPAL_CLIENT_ID !== '' && PAYPAL_SECRET !== '';
}

// Hace una petición a la API de PayPal y regresa [código HTTP, respuesta como arreglo].
function paypalPeticion($metodo, $ruta, $cuerpo = null, $token = null)
{
    $ch = curl_init(PAYPAL_URL_BASE . $ruta);
    $encabezados = [];
    $opciones = [
        CURLOPT_CUSTOMREQUEST  => $metodo,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ];

    if ($token === null) {
        // Pedir token de acceso con usuario = Client ID y contraseña = Secret
        $opciones[CURLOPT_USERPWD] = PAYPAL_CLIENT_ID . ':' . PAYPAL_SECRET;
        $opciones[CURLOPT_POSTFIELDS] = 'grant_type=client_credentials';
        $encabezados[] = 'Content-Type: application/x-www-form-urlencoded';
    } else {
        $encabezados[] = 'Authorization: Bearer ' . $token;
        $encabezados[] = 'Content-Type: application/json';
        $opciones[CURLOPT_POSTFIELDS] = $cuerpo === null ? '' : json_encode($cuerpo);
    }
    $opciones[CURLOPT_HTTPHEADER] = $encabezados;
    curl_setopt_array($ch, $opciones);

    $respuesta = curl_exec($ch);
    if ($respuesta === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('No se pudo conectar con PayPal: ' . $error);
    }
    $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$codigo, json_decode($respuesta, true) ?: []];
}

function paypalToken()
{
    [$codigo, $datos] = paypalPeticion('POST', '/v1/oauth2/token');
    if ($codigo !== 200 || empty($datos['access_token'])) {
        throw new RuntimeException('PayPal rechazó las llaves (revisa PAYPAL_CLIENT_ID y PAYPAL_SECRET).');
    }
    return $datos['access_token'];
}

// Crea una orden por el total calculado en el servidor y regresa su ID.
function paypalCrearOrden($total)
{
    [$codigo, $datos] = paypalPeticion('POST', '/v2/checkout/orders', [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'description' => 'Compra en RULFOOS',
            'amount' => ['currency_code' => PAYPAL_MONEDA, 'value' => number_format($total, 2, '.', '')],
        ]],
    ], paypalToken());

    if ($codigo !== 201 && $codigo !== 200 || empty($datos['id'])) {
        throw new RuntimeException('PayPal no pudo crear la orden (HTTP ' . $codigo . ').');
    }
    return $datos['id'];
}

// Cobra una orden que el cliente ya aprobó. Regresa el monto cobrado.
function paypalCapturar($ordenId)
{
    [$codigo, $datos] = paypalPeticion('POST', '/v2/checkout/orders/' . rawurlencode($ordenId) . '/capture', null, paypalToken());

    $captura = $datos['purchase_units'][0]['payments']['captures'][0] ?? null;
    if (($codigo !== 201 && $codigo !== 200) || ($datos['status'] ?? '') !== 'COMPLETED'
        || !$captura || ($captura['status'] ?? '') !== 'COMPLETED') {
        throw new RuntimeException('PayPal no completó el cobro.');
    }
    if (($captura['amount']['currency_code'] ?? '') !== PAYPAL_MONEDA) {
        throw new RuntimeException('PayPal cobró en una moneda distinta.');
    }
    return (float) $captura['amount']['value'];
}
