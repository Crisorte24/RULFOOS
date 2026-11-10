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

// Función para obtener todas las promociones
function obtenerPromociones() {
    global $conn;
    $sql = "SELECT * FROM promociones";
    $resultado = mysqli_query($conn, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

// Función para agregar una nueva promoción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre_promocion = $_POST['nombre_promocion'];
    $precio_original = $_POST['precio_original'];
    $precio_descuento = $_POST['precio_descuento'];
    $directorio = 'imgg/';
    $archivo = $directorio . basename($_FILES['imagen']['name']);
    move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo);

    $sql = "INSERT INTO promociones (nombre_promocion, precio_original, precio_descuento, imag)
            VALUES ('$nombre_promocion', '$precio_original', '$precio_descuento', '$archivo')";

    if (mysqli_query($conn, $sql)) {
        actualizarPaginaEstatica();
    }
}

// Función para editar una promoción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_cambios'])) {
    $id_promocion = $_POST['id_promocion'];
    $nombre_promocion = $_POST['nombre_promocion'];
    $precio_original = $_POST['precio_original'];
    $precio_descuento = $_POST['precio_descuento'];

    // Inicializar la consulta de actualización
    $sql = "UPDATE promociones SET nombre_promocion='$nombre_promocion', precio_original='$precio_original', precio_descuento='$precio_descuento'";

    // Verificar si hay una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $directorio = 'imgg/';
        $archivo = $directorio . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo);
        $sql .= ", imag='$archivo'"; // Agregar la imagen a la consulta
    }

    $sql .= " WHERE id_promocion='$id_promocion'"; // Especificar cuál promoción se debe actualizar

    if (mysqli_query($conn, $sql)) {
        actualizarPaginaEstatica(); // Actualizar la página estática si la consulta fue exitosa
    } else {
        echo "Error al actualizar la promoción: " . mysqli_error($conn); // Mensaje de error
    }
}

// Función para eliminar una promoción
if (isset($_GET['eliminar'])) {
    $id_promocion = $_GET['eliminar'];
    $sql = "DELETE FROM promociones WHERE id_promocion='$id_promocion'";
    if (mysqli_query($conn, $sql)) {
        actualizarPaginaEstatica();
    }
}

// Función para actualizar la página estática
function actualizarPaginaEstatica() {
    $promociones = obtenerPromociones();
    $contenidoHTML = '<section class="promos-container" id="lista-1">
        <h2>Promociones</h2>
        <div class="categories">';
    
    foreach ($promociones as $promo) {
        $contenidoHTML .= '
        <div class="categorie">
            <div class="categoria-1">
                <h3>' . $promo['nombre_promocion'] . '</h3>
                <div class="prices">
                    <p class="price-1">$' . number_format($promo['precio_original'], 2) . '</p>
                    <p class="precio">$' . number_format($promo['precio_descuento'], 2) . '</p>
                </div>
                <a href="#" class="agregar-carrito btn-3" data-id="' . $promo['id_promocion'] . '">Agregar al carrito</a>
            </div>
            <div class="categoria-img">
                <img src="' . $promo['imag'] . '" alt="Promo">
            </div>
        </div>';
    }

    $contenidoHTML .= '</div></section>';

    file_put_contents('promociones.html', $contenidoHTML);
}

// Obtener todas las promociones para mostrarlas en la administración
$promociones = obtenerPromociones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo promos.css"> <!-- Corrige el nombre del archivo CSS si es necesario -->
    <title>Administrar Promociones</title>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h1>Administrar Promociones</h1>

        <!-- Formulario para agregar una nueva promoción -->
        <form action="admin_promociones.php" method="POST" enctype="multipart/form-data">
            <h2>Agregar Nueva Promoción</h2>
            <label for="nombre_promocion">Nombre de la Promoción:</label>
            <input type="text" name="nombre_promocion" required><br>

            <label for="precio_original">Precio Original:</label>
            <input type="number" step="0.01" name="precio_original" required><br>

            <label for="precio_descuento">Precio con Descuento:</label>
            <input type="number" step="0.01" name="precio_descuento" required><br>

            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" required><br>

            <button type="submit" name="agregar" class="login-btn">Agregar Promoción</button>
        </form>

        <hr>

        <!-- Lista de promociones existentes -->
        <h2>Promociones Existentes</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio Original</th>
                    <th>Precio con Descuento</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
                    <tbody>
            <?php foreach ($promociones as $promo): ?>
            <tr>
                <form action="admin_promociones.php" method="POST" enctype="multipart/form-data">
                    <td><input type="text" name="nombre_promocion" value="<?php echo htmlspecialchars($promo['nombre_promocion']); ?>" required></td>
                    <td><input type="number" step="0.01" name="precio_original" value="<?php echo htmlspecialchars($promo['precio_original']); ?>" required></td>
                    <td><input type="number" step="0.01" name="precio_descuento" value="<?php echo htmlspecialchars($promo['precio_descuento']); ?>" required></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($promo['imag']); ?>" alt="Promo" width="100">
                        <input type="file" name="imagen">
                    </td>
                    <td>
                        <input type="hidden" name="id_promocion" value="<?php echo htmlspecialchars($promo['id_promocion']); ?>">
                        <button type="submit" name="guardar_cambios" class="login-btn">Guardar Cambios</button>
                        <a href="admin_promociones.php?eliminar=<?php echo htmlspecialchars($promo['id_promocion']); ?>" class="btn-del">Eliminar</a>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>

        </table>

        <!-- Botón para regresar al menú -->
        <button onclick="window.location.href='menu_add.php';" class="login-btn">Regresar al Menú</button>
    </div>
</div>

</body>
</html>
