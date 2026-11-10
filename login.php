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

// Si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // El usuario existe, ahora verifica la contraseña
            $row = $result->fetch_assoc();
            if ($row['contrasena'] === $contrasena) {
                $_SESSION['usuario'] = $usuario; // Almacena el usuario en la sesión

                // Si se selecciona "Recordar", se crean las cookies
                if (isset($_POST['remember'])) {
                    setcookie("usuario", $usuario, time() + (86400 * 30), "/"); // Expira en 30 días
                    setcookie("contrasena", $contrasena, time() + (86400 * 30), "/"); // Expira en 30 días
                } else {
                    // Si no se selecciona "Recordar", se eliminan las cookies
                    setcookie("usuario", "", time() - 3600, "/");
                    setcookie("contrasena", "", time() - 3600, "/");
                }

                header("Location: menu_add.php"); // Redirige al menú
                exit();
            } else {
                $error = "Contraseña incorrecta."; // Mensaje de error para contraseña
            }
        } else {
            $error = "Usuario incorrecto."; // Mensaje de error para usuario
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
    <title>Login</title>
    <link rel="stylesheet" href="login_sty.css"> <!-- Enlace a la hoja de estilos -->
    <link rel="stylesheet" href="estilo login.css">
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("contrasena");
            const passwordToggle = document.getElementById("toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggle.textContent = "Ocultar";
            } else {
                passwordInput.type = "password";
                passwordToggle.textContent = "Mostrar";
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido a RULFOOS</h2>
            <form method="POST" action="">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required
                       value="<?php echo isset($_COOKIE['usuario']) ? htmlspecialchars($_COOKIE['usuario']) : ''; ?>">
                <br>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required
                       value="<?php echo isset($_COOKIE['contrasena']) ? htmlspecialchars($_COOKIE['contrasena']) : ''; ?>">
                <span id="toggle-password" class="toggle-password" onclick="togglePassword()">Mostrar</span>
                <br>
                <label><input type="checkbox" name="remember" 
                    <?php echo isset($_COOKIE['usuario']) ? 'checked' : ''; ?>>Recordar</label>
                <br>
                <button type="submit" class="btn-1">Entrar</button>
                <a href="inicio rulfoos.php" class="btn-1">Inicio </a>    
            </form>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        </div>
    </div>
</body>
</html>
