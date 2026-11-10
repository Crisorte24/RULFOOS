<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto según tu configuración
$password = ""; // Cambia esto según tu configuración
$dbname = "gool"; // Cambia esto por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha recibido un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la imagen del producto antes de eliminarlo para eliminarla del servidor
    $result = $conn->query("SELECT imagen FROM productos WHERE id='$id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagen = $row['imagen'];

        // Eliminar el producto de la base de datos
        $sql = "DELETE FROM productos WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            // Eliminar la imagen del servidor
            if (file_exists($imagen)) {
                unlink($imagen);
            }
            echo "<script>alert('Producto eliminado exitosamente.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el producto: " . $conn->error . "'); window.location.href='admin.php';</script>";
        }
    } else {
        echo "<script>alert('Producto no encontrado.'); window.location.href='admin.php';</script>";
    }
} else {
    echo "<script>alert('No se recibió un ID.'); window.location.href='admin.php';</script>";
}

$conn->close();
?>
