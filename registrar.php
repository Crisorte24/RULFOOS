<?php
// Recibe el formulario de "contacto rulfos php.php", lo guarda en la tabla `contactos`
// y regresa a la página de contacto con un mensaje de éxito o error.
require 'conexion.php';

$volver = 'contacto rulfos php.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $volver);
    exit();
}

$nombre  = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$asunto  = trim($_POST['asunto'] ?? '');
$mensaje = trim($_POST['mesage'] ?? '');

if ($nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || mb_strlen($nombre) > 100 || mb_strlen($email) > 150 || mb_strlen($asunto) > 150) {
    header('Location: ' . $volver . '?estado=error');
    exit();
}

$stmt = $conn->prepare('INSERT INTO contactos (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $nombre, $email, $asunto, $mensaje);
$stmt->execute();
$conn->close();

header('Location: ' . $volver . '?estado=enviado');
exit();
