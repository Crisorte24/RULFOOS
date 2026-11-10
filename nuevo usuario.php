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


$usuarios = $conn->query('SELECT usuario FROM usuarios ORDER BY usuario')->fetch_all(MYSQLI_ASSOC);

$titulo = 'Usuarios';
$seccion = 'usuarios';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Usuarios</h1>
                <p>Personas que pueden entrar al panel de administración.</p>
            </div>
        </div>

        <?php if ($error): ?>
            <p class="aviso mal"><?php echo e($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="aviso ok"><?php echo e($success); ?></p>
        <?php endif; ?>

        <div class="form-grid">
            <section class="tarjeta admin-seccion">
                <h2>Dar de alta un usuario</h2>
                <form method="POST" action="">
                    <div class="campo">
                        <label for="usuario">Nombre de usuario</label>
                        <input type="text" id="usuario" name="usuario" maxlength="50" required autocomplete="off">
                    </div>
                    <div class="campo">
                        <label for="contrasena">Contraseña <small>(mínimo 6 caracteres)</small></label>
                        <input type="password" id="contrasena" name="contrasena" minlength="6" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primario">Registrar usuario</button>
                </form>
            </section>

            <section class="tarjeta admin-seccion">
                <h2>Usuarios actuales</h2>
                <ul class="lista-usuarios">
                    <?php foreach ($usuarios as $u): ?>
                        <li><i class="fa-solid fa-user" aria-hidden="true"></i> <?php echo e($u['usuario']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>
<?php
require 'admin_pie.php';
