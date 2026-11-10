<?php
// Conexión única a la base de datos. Todas las páginas la incluyen con:
//   require 'conexion.php';
// Si cambias de servidor o contraseña, solo hay que cambiarlo aquí.
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'gool';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    error_log('Error de conexión a MySQL: ' . $ex->getMessage());
    http_response_code(500);
    exit('No se pudo conectar a la base de datos. Revisa que MySQL esté encendido en XAMPP.');
}

// Escapa texto antes de mostrarlo en HTML (evita que alguien inyecte código).
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}
