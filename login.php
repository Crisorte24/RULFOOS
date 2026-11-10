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
    <meta name="robots" content="noindex">
    <title>Iniciar sesión | Admin RULFOOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/login.js" defer></script>
</head>
<body class="pagina-login">
    <main class="tarjeta login-tarjeta">
        <a href="inicio rulfoos.php" class="logo">RULFOOS<span>Accesorios</span></a>
        <h1>Bienvenido</h1>
        <p class="subtitulo">Inicia sesión para administrar la tienda</p>

        <?php if ($error !== ''): ?>
            <p class="aviso mal"><?php echo e($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="campo">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required autocomplete="username"
                       value="<?php echo e($_POST['usuario'] ?? $_COOKIE['usuario'] ?? ''); ?>">
            </div>
            <div class="campo">
                <label for="contrasena">Contraseña</label>
                <div class="campo-contrasena">
                    <input type="password" id="contrasena" name="contrasena" required autocomplete="current-password">
                    <button type="button" id="toggle-password" class="toggle-password" onclick="togglePassword()">Mostrar</button>
                </div>
            </div>
            <label class="recordar">
                <input type="checkbox" name="remember" <?php echo isset($_COOKIE['usuario']) ? 'checked' : ''; ?>>
                Recordar mi usuario
            </label>
            <button type="submit" class="btn btn-primario btn-bloque">Entrar</button>
        </form>

        <a href="inicio rulfoos.php" class="login-volver">&larr; Volver a la tienda</a>
    </main>
</body>
</html>
