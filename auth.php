<?php
// Incluye este archivo al inicio de cualquier página de administración.
// Si nadie ha iniciado sesión, manda al login.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Mensajes de una sola vez (se muestran después de redirigir).
function flash($mensaje = null)
{
    if ($mensaje !== null) {
        $_SESSION['flash'] = $mensaje;
        return null;
    }
    $m = $_SESSION['flash'] ?? '';
    unset($_SESSION['flash']);
    return $m;
}
