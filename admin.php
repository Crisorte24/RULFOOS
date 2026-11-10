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

// Manejo de la carga de la imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = "imgg/"; // Carpeta para imágenes de productos
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

    $qr_dir = "imgg/qr/";
    if (!is_dir($qr_dir)) {
        mkdir($qr_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $nombre = $_POST['nombre'];
        $precio1 = $_POST['precio1'];
        $precio2 = $_POST['precio2'];
        $cantidad = $_POST['cantidad']; // Capturar cantidad
        $url_sketchfab = $_POST['url_sketchfab'];

        $imagen_qr = null;
        if (!empty($_FILES["imagen_qr"]["name"])) {
            $qr_file = $qr_dir . basename($_FILES["imagen_qr"]["name"]);
            if (move_uploaded_file($_FILES["imagen_qr"]["tmp_name"], $qr_file)) {
                $imagen_qr = $qr_file;
            } else {
                echo "<script>alert('Error al mover la imagen QR');</script>";
            }
        }

        if (isset($_POST['id']) && $_POST['id'] != "") {
            $id = $_POST['id'];
            $sql = "UPDATE productos SET nombre='$nombre', precio1='$precio1', precio2='$precio2', cantidad='$cantidad', imagen='$target_file', url_sketchfab='$url_sketchfab'";
            if ($imagen_qr) {
                $sql .= ", imagen_qr='$imagen_qr'";
            }
            $sql .= " WHERE id='$id'";
        } else {
            $sql = "INSERT INTO productos (nombre, precio1, precio2, cantidad, imagen, imagen_qr, url_sketchfab) 
                    VALUES ('$nombre', '$precio1', '$precio2', '$cantidad', '$target_file', '$imagen_qr', '$url_sketchfab')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registro exitoso.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error al cargar la imagen del producto.');</script>";
    }
}

// Obtener productos para mostrar en el formulario
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estlilo admin.css">
    <title>Administración de Productos</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Administración de Productos</h1>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                <label>Nombre:</label>
                <input type="text" name="nombre" required placeholder="Ingrese el nombre">
                <label>Precio 1:</label>
                <input type="text" name="precio1" required placeholder="Ingrese el precio 1">
                <label>Precio 2:</label>
                <input type="text" name="precio2" required placeholder="Ingrese el precio 2">
                <label>Cantidad:</label>  <!-- Nuevo campo -->
                <input type="number" name="cantidad" required placeholder="Ingrese la cantidad">
                <label>Imagen:</label>
                <input type="file" name="imagen" accept="image/*" required>
                <label>Imagen QR:</label>
                <input type="file" name="imagen_qr" accept="image/*">
                <label>URL del Modelo 3D de Sketchfab:</label>
                <input type="url" name="url_sketchfab" placeholder="Ingrese la URL del modelo de Sketchfab" required>

                <button type="submit" class="login-btn">Guardar Producto</button>
            </form>

            <h2>Lista de Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio 1</th>
                        <th>Precio 2</th>
                        <th>Cantidad</th>  <!-- Nueva columna -->
                        <th>Imagen</th>
                        <th>Imagen QR</th>
                        <th>URL de Sketchfab</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['precio1']; ?></td>
                            <td><?php echo $row['precio2']; ?></td>
                            <td><?php echo $row['cantidad']; ?></td> <!-- Mostrar cantidad -->
                            <td><img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" width="100"></td>

                            <td>
                                <?php if (!empty($row['imagen_qr'])): ?>
                                    <img src="<?php echo $row['imagen_qr']; ?>" alt="QR <?php echo $row['nombre']; ?>" width="100">
                                <?php else: ?>
                                    <p>No disponible</p>
                                <?php endif; ?>
                            </td>

                            <td><a href="<?php echo $row['url_sketchfab']; ?>" target="_blank"><?php echo $row['url_sketchfab']; ?></a></td>

                            <td>
                                <button onclick="editProduct(<?php echo $row['id']; ?>, '<?php echo $row['nombre']; ?>', '<?php echo $row['precio1']; ?>', '<?php echo $row['precio2']; ?>', '<?php echo $row['cantidad']; ?>', '<?php echo $row['imagen']; ?>', '<?php echo $row['imagen_qr']; ?>', '<?php echo $row['url_sketchfab']; ?>')" class="btn-1">Editar</button>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-del">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button onclick="window.location.href='menu_add.php';" class="btn-1">Regresar al Menú</button>
        </div>
    </div>

    <script>
    function editProduct(id, nombre, precio1, precio2, cantidad, imagen, imagen_qr, url_sketchfab) {
        document.querySelector('[name="id"]').value = id;
        document.querySelector('[name="nombre"]').value = nombre;
        document.querySelector('[name="precio1"]').value = precio1;
        document.querySelector('[name="precio2"]').value = precio2;
        document.querySelector('[name="cantidad"]').value = cantidad;
        document.querySelector('[name="url_sketchfab"]').value = url_sketchfab;
        alert("Cantidad actual: " + cantidad + "\nImagen: " + imagen + "\nImagen QR: " + imagen_qr + "\nURL de Sketchfab: " + url_sketchfab);
    }
    </script>

</body>
</html>
