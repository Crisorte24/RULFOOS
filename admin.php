<?php
require 'auth.php';          // Solo administradores con sesión.
require 'conexion.php';
require 'subir_imagen.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int) ($_POST['id'] ?? 0);           // 0 = producto nuevo
    $nombre    = trim($_POST['nombre'] ?? '');
    $precio1   = (int) ($_POST['precio1'] ?? 0);
    $precio2   = (int) ($_POST['precio2'] ?? 0);
    $cantidad  = (int) ($_POST['cantidad'] ?? 0);
    $url_3d    = trim($_POST['url_3d'] ?? '');

    try {
        if ($nombre === '' || mb_strlen($nombre) > 50) {
            throw new RuntimeException('El nombre debe tener entre 1 y 50 caracteres.');
        }
        if ($url_3d !== '' && !filter_var($url_3d, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('La URL de realidad aumentada no es válida.');
        }

        $imagen    = guardarImagen('imagen', 'imgg');
        $imagen_qr = guardarImagen('imagen_qr', 'imgg/qr');

        if ($id > 0) {
            // Editar: si no se sube imagen nueva, se conserva la anterior.
            $stmt = $conn->prepare('SELECT imagen, imagen_qr FROM productos WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $actual = $stmt->get_result()->fetch_assoc();
            if (!$actual) {
                throw new RuntimeException('El producto ya no existe.');
            }

            $nuevaImagen = $imagen ?? $actual['imagen'];
            $nuevoQr     = $imagen_qr ?? $actual['imagen_qr'];

            $stmt = $conn->prepare(
                'UPDATE productos SET nombre = ?, precio1 = ?, precio2 = ?, cantidad = ?,
                        imagen = ?, imagen_qr = ?, url_3d = ? WHERE id = ?'
            );
            $stmt->bind_param('siiisssi', $nombre, $precio1, $precio2, $cantidad,
                              $nuevaImagen, $nuevoQr, $url_3d, $id);
            $stmt->execute();

            // Borra las imágenes viejas que se reemplazaron.
            if ($imagen)    { borrarImagenSiNoSeUsa($conn, $actual['imagen']); }
            if ($imagen_qr) { borrarImagenSiNoSeUsa($conn, $actual['imagen_qr']); }

            flash('Producto actualizado.');
        } else {
            if ($imagen === null) {
                throw new RuntimeException('La imagen del producto es obligatoria.');
            }
            $stmt = $conn->prepare(
                'INSERT INTO productos (nombre, precio1, precio2, cantidad, imagen, imagen_qr, url_3d)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('siiisss', $nombre, $precio1, $precio2, $cantidad,
                              $imagen, $imagen_qr, $url_3d);
            $stmt->execute();
            flash('Producto registrado.');
        }
    } catch (RuntimeException $ex) {
        flash('Error: ' . $ex->getMessage());
    }

    // Redirigir evita que el producto se guarde dos veces al recargar la página.
    header('Location: admin.php');
    exit();
}

$mensaje = flash();
$result = $conn->query('SELECT * FROM productos ORDER BY id');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Administración de Productos</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Administración de Productos</h1>

            <?php if ($mensaje): ?>
                <p class="<?php echo strpos($mensaje, 'Error') === 0 ? 'error' : 'success'; ?>"><?php echo e($mensaje); ?></p>
            <?php endif; ?>

            <h2 id="titulo-form">Nuevo producto</h2>
            <form method="POST" enctype="multipart/form-data" id="form-producto">
                <input type="hidden" name="id" value="">
                <label>Nombre:</label>
                <input type="text" name="nombre" maxlength="50" required placeholder="Ingrese el nombre">
                <label>Precio 1:</label>
                <input type="number" name="precio1" min="0" step="1" required placeholder="Ingrese el precio 1">
                <label>Precio 2:</label>
                <input type="number" name="precio2" min="0" step="1" required placeholder="Ingrese el precio 2">
                <label>Cantidad:</label>
                <input type="number" name="cantidad" min="0" step="1" required placeholder="Ingrese la cantidad">
                <label>Imagen: <small id="nota-imagen"></small></label>
                <input type="file" name="imagen" accept="image/*" required>
                <label>Imagen QR:</label>
                <input type="file" name="imagen_qr" accept="image/*">
                <label>URL de realidad aumentada / 3D:</label>
                <input type="url" name="url_3d" placeholder="https://mywebar.com/p/...">

                <button type="submit" class="login-btn">Guardar Producto</button>
                <button type="button" class="btn-1" id="cancelar-edicion" hidden>Cancelar edición</button>
            </form>

            <h2>Lista de Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio 1</th>
                        <th>Precio 2</th>
                        <th>Cantidad</th>
                        <th>Imagen</th>
                        <th>Imagen QR</th>
                        <th>URL realidad aumentada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int) $row['id']; ?></td>
                            <td><?php echo e($row['nombre']); ?></td>
                            <td><?php echo e($row['precio1']); ?></td>
                            <td><?php echo e($row['precio2']); ?></td>
                            <td><?php echo e($row['cantidad']); ?></td>
                            <td><img src="<?php echo e($row['imagen']); ?>" alt="<?php echo e($row['nombre']); ?>" width="100"></td>
                            <td>
                                <?php if (!empty($row['imagen_qr'])): ?>
                                    <img src="<?php echo e($row['imagen_qr']); ?>" alt="QR <?php echo e($row['nombre']); ?>" width="100">
                                <?php else: ?>
                                    <p>No disponible</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['url_3d'])): ?>
                                    <a href="<?php echo e($row['url_3d']); ?>" target="_blank" rel="noopener"><?php echo e($row['url_3d']); ?></a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn-1 btn-editar"
                                        data-id="<?php echo (int) $row['id']; ?>"
                                        data-nombre="<?php echo e($row['nombre']); ?>"
                                        data-precio1="<?php echo e($row['precio1']); ?>"
                                        data-precio2="<?php echo e($row['precio2']); ?>"
                                        data-cantidad="<?php echo e($row['cantidad']); ?>"
                                        data-url3d="<?php echo e($row['url_3d']); ?>">Editar</button>
                                <form method="POST" action="delete.php" class="form-inline"
                                      onsubmit="return confirm('¿Eliminar este producto?');">
                                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                    <button type="submit" class="btn-del">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button onclick="window.location.href='menu_add.php';" class="btn-1">Regresar al Menú</button>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>
