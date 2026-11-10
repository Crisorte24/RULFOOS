<?php
$conex = mysqli_connect("localhost", "root", "", "contactos_rulfoos");

if (!$conex) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conex, $_POST['name']);
    $email = mysqli_real_escape_string($conex, $_POST['email']);
    $asunto = mysqli_real_escape_string($conex, $_POST['asunto']);
    $mensaje = mysqli_real_escape_string($conex, $_POST['mesage']);

    $sql = "INSERT INTO datos (nombre, email, asunto, mesage) VALUES ('$nombre', '$email', '$asunto', '$mensaje')";
    if (mysqli_query($conex, $sql)) {
        echo "Datos insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos: " . mysqli_error($conex);
    }
}

mysqli_close($conex);
?>
