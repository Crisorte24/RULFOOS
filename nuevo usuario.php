<?php
require 'auth.php';      // Solo un administrador con sesión puede dar de alta usuarios.
require 'conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($usuario === '' || strlen($usuario) > 50) {
        $error = 'El nombre de usuario debe tener entre 1 y 50 caracteres.';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM usuarios WHERE usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            $error = 'El usuario ya existe.';
        } else {
            // La contraseña se guarda cifrada, nunca en texto plano.
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, huella_dactilar) VALUES (?, ?, '')");
            $ins->bind_param('ss', $usuario, $hash);
            $ins->execute();
            $success = 'Usuario registrado exitosamente.';
        }
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
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h2>Registro de Usuario</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo e($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo e($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" maxlength="50" required autocomplete="off">

        <label for="contrasena">Contraseña (mínimo 6 caracteres):</label>
        <input type="password" id="contrasena" name="contrasena" minlength="6" required autocomplete="new-password">

        <button type="submit" class="btn-1">Registrar</button>
    </form>

    <button onclick="window.location.href='menu_add.php';" class="btn-1">Regresar al Menú</button>
</body>
</html>
