<?php
session_start();
require 'conexion.php';

// Si ya inició sesión, directo al menú.
if (isset($_SESSION['usuario'])) {
    header('Location: menu_add.php');
    exit();
}

// Por seguridad ya no se guarda la contraseña en cookies: borra la que haya quedado de antes.
if (isset($_COOKIE['contrasena'])) {
    setcookie('contrasena', '', time() - 3600, '/');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Consulta preparada: evita inyección SQL.
    $stmt = $conn->prepare('SELECT id, contrasena FROM usuarios WHERE usuario = ?');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    $valido = false;
    if ($row) {
        if (password_verify($contrasena, $row['contrasena'])) {
            $valido = true;
        } elseif (password_get_info($row['contrasena'])['algo'] === null
                  && hash_equals($row['contrasena'], $contrasena)) {
            // Contraseña antigua guardada sin cifrar: es correcta, así que la ciframos ahora.
            $valido = true;
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $upd = $conn->prepare('UPDATE usuarios SET contrasena = ? WHERE id = ?');
            $upd->bind_param('si', $hash, $row['id']);
            $upd->execute();
        }
    }

    if ($valido) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;

        // "Recordar" solo guarda el nombre de usuario, nunca la contraseña.
        if (isset($_POST['remember'])) {
            setcookie('usuario', $usuario, time() + (86400 * 30), '/');
        } else {
            setcookie('usuario', '', time() - 3600, '/');
        }

        header('Location: menu_add.php');
        exit();
    }

    $error = 'Usuario o contraseña incorrectos.';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login_sty.css"> <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="css/login.css">

    <script src="js/login.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido a RULFOOS</h2>
            <form method="POST" action="">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required autocomplete="username"
                       value="<?php echo e($_POST['usuario'] ?? $_COOKIE['usuario'] ?? ''); ?>">
                <br>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">
                <span id="toggle-password" class="toggle-password" onclick="togglePassword()">Mostrar</span>
                <br>
                <label><input type="checkbox" name="remember"
                    <?php echo isset($_COOKIE['usuario']) ? 'checked' : ''; ?>>Recordar</label>
                <br>
                <button type="submit" class="btn-1">Entrar</button>
                <a href="inicio rulfoos.php" class="btn-1">Inicio</a>
            </form>
            <?php if ($error !== ''): ?>
                <p class="error"><?php echo e($error); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
