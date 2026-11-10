<?php
$conex = mysqli_connect("localhost", "root", "", "contactos_rulfoos");

if (!$conex) {
    die("Conexión fallida: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa a la base de datos.<br>";
}
?>