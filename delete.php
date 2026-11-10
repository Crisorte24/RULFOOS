<?php
// Elimina un producto. Solo acepta POST desde admin.php con sesión iniciada.
require 'auth.php';
require 'conexion.php';
require 'subir_imagen.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header('Location: admin.php');
    exit();
}

$id = (int) $_POST['id'];

$stmt = $conn->prepare('SELECT imagen, imagen_qr FROM productos WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    flash('Error: Producto no encontrado.');
} else {
    $stmt = $conn->prepare('DELETE FROM productos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Borra sus imágenes del servidor (si ningún otro producto las usa).
    borrarImagenSiNoSeUsa($conn, $producto['imagen']);
    borrarImagenSiNoSeUsa($conn, $producto['imagen_qr']);

    flash('Producto eliminado exitosamente.');
}

$conn->close();
header('Location: admin.php');
exit();
