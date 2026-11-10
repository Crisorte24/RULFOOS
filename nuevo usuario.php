<?php
session_start();
$host = 'localhost'; // Cambia si tu base de datos está en otro servidor
$user = 'root'; // Cambia por tu usuario de base de datos
$password = ''; // Cambia por tu contraseña de base de datos
$dbname = 'gool';

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = ''; // Inicializa la variable de error
$success = ''; // Inicializa la variable de éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $error = "El usuario ya existe."; // Mensaje de error para usuario existente
        } else {
            // Inserta el nuevo usuario
            $sql_insert = "INSERT INTO usuarios (usuario, contrasena) VALUES ('$usuario', '$contrasena')";
            if ($conn->query($sql_insert) === TRUE) {
                $success = "Usuario registrado exitosamente."; // Mensaje de éxito
            } else {
                $error = "Error al registrar el usuario: " . $conn->error; // Mensaje de error en caso de fallo en la inserción
            }
        }
    } else {
        $error = "Error en la consulta: " . $conn->error; // Mensaje de error en caso de fallo en la consulta
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estlilo admin.css"> <!-- Asegúrate de tener un archivo CSS si quieres estilos -->
</head>
<body>
    <h2>Registro de Usuario</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" required>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        
        <button type="submit">Registrar</button>

        
    </form>

    <button onclick="window.location.href='menu_add.php';" class="btn-1">Regresar al Menú</button>
</body>
</html>
