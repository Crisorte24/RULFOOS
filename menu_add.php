<?php
require 'auth.php';
require 'conexion.php'; // para la función e()
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="main-container">
        <!-- Menú de navegación en la parte izquierda -->
        <div class="menu">
            <h2>Bienvenido a Rulfoos Administrador <?php echo e($_SESSION['usuario']); ?></h2>
            <button onclick="window.location.href='admin.php';" class="btn-1">Administrar Productos</button>
            <button onclick="window.location.href='admin_promociones.php';" class="btn-1">Administrar Promociones</button>
            <button onclick="window.location.href='nuevo usuario.php';" class="btn-1">Dar de alta nuevo usuario</button>
            <br><button id="botonApi" class="btn-1">Reporte Inventario</button><br>

            <br>
            <a href="logout.php" class="btn-1">Cerrar sesión</a>
        </div>

        <!-- Contenido o espacio libre al lado derecho del menú -->
        <div class="content"></div>
    </div>

<script src="js/menu.js"></script>
</body>
</html>
