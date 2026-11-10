<?php
// Devuelve en JSON los productos cuyo nombre contiene el texto buscado (?q=...).
header('Content-Type: application/json; charset=utf-8');
require 'conexion.php';

$query = trim($_GET['q'] ?? '');
$productos = [];

if ($query !== '') {
    $stmt = $conn->prepare('SELECT id, nombre FROM productos WHERE nombre LIKE ? ORDER BY nombre LIMIT 10');
    $busqueda = '%' . $query . '%';
    $stmt->bind_param('s', $busqueda);
    $stmt->execute();
    $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
echo json_encode($productos);
