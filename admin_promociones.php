<?php
require 'auth.php';          // Solo administradores con sesión.
require 'conexion.php';
require 'subir_imagen.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    try {
        if ($accion === 'agregar' || $accion === 'guardar') {
            $nombre           = trim($_POST['nombre_promocion'] ?? '');
            $precio_original  = (float) ($_POST['precio_original'] ?? 0);
            $precio_descuento = (float) ($_POST['precio_descuento'] ?? 0);

            if ($nombre === '' || mb_strlen($nombre) > 50) {
                throw new RuntimeException('El nombre debe tener entre 1 y 50 caracteres.');
            }
            $imagen = guardarImagen('imagen', 'imgg');
        }

        if ($accion === 'agregar') {
            if ($imagen === null) {
                throw new RuntimeException('La imagen es obligatoria.');
            }
            $stmt = $conn->prepare(
                'INSERT INTO promociones (nombre_promocion, precio_original, precio_descuento, imag) VALUES (?, ?, ?, ?)'
            );
            $stmt->bind_param('sdds', $nombre, $precio_original, $precio_descuento, $imagen);
            $stmt->execute();
            flash('Promoción agregada.');

        } elseif ($accion === 'guardar') {
            $id = (int) ($_POST['id_promocion'] ?? 0);
            $stmt = $conn->prepare('SELECT imag FROM promociones WHERE id_promocion = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $actual = $stmt->get_result()->fetch_assoc();
            if (!$actual) {
                throw new RuntimeException('La promoción ya no existe.');
            }

            $nuevaImagen = $imagen ?? $actual['imag'];
            $stmt = $conn->prepare(
                'UPDATE promociones SET nombre_promocion = ?, precio_original = ?, precio_descuento = ?, imag = ?
                 WHERE id_promocion = ?'
            );
            $stmt->bind_param('sddsi', $nombre, $precio_original, $precio_descuento, $nuevaImagen, $id);
            $stmt->execute();
            if ($imagen) {
                borrarImagenSiNoSeUsa($conn, $actual['imag']);
            }
            flash('Promoción actualizada.');

        } elseif ($accion === 'eliminar') {
            $id = (int) ($_POST['id_promocion'] ?? 0);
            $stmt = $conn->prepare('SELECT imag FROM promociones WHERE id_promocion = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $actual = $stmt->get_result()->fetch_assoc();

            $stmt = $conn->prepare('DELETE FROM promociones WHERE id_promocion = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            if ($actual) {
                borrarImagenSiNoSeUsa($conn, $actual['imag']);
            }
            flash('Promoción eliminada.');
        }
    } catch (RuntimeException $ex) {
        flash('Error: ' . $ex->getMessage());
    }

    header('Location: admin_promociones.php');
    exit();
}

$mensaje = flash();
$promociones = $conn->query('SELECT * FROM promociones ORDER BY id_promocion')->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/promociones.css">
    <title>Administrar Promociones</title>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h1>Administrar Promociones</h1>

        <?php if ($mensaje): ?>
            <p class="<?php echo strpos($mensaje, 'Error') === 0 ? 'error' : 'success'; ?>"><?php echo e($mensaje); ?></p>
        <?php endif; ?>

        <!-- Formulario para agregar una nueva promoción -->
        <form action="admin_promociones.php" method="POST" enctype="multipart/form-data">
            <h2>Agregar Nueva Promoción</h2>
            <input type="hidden" name="accion" value="agregar">

            <label for="nombre_promocion">Nombre de la Promoción:</label>
            <input type="text" id="nombre_promocion" name="nombre_promocion" maxlength="50" required><br>

            <label for="precio_original">Precio Original:</label>
            <input type="number" id="precio_original" step="0.01" min="0" name="precio_original" required><br>

            <label for="precio_descuento">Precio con Descuento:</label>
            <input type="number" id="precio_descuento" step="0.01" min="0" name="precio_descuento" required><br>

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required><br>

            <button type="submit" class="login-btn">Agregar Promoción</button>
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
            <?php foreach ($promociones as $promo): $fid = 'promo-' . (int) $promo['id_promocion']; ?>
                <tr>
                    <td><input form="<?php echo $fid; ?>" type="text" name="nombre_promocion" maxlength="50" value="<?php echo e($promo['nombre_promocion']); ?>" required></td>
                    <td><input form="<?php echo $fid; ?>" type="number" step="0.01" min="0" name="precio_original" value="<?php echo e($promo['precio_original']); ?>" required></td>
                    <td><input form="<?php echo $fid; ?>" type="number" step="0.01" min="0" name="precio_descuento" value="<?php echo e($promo['precio_descuento']); ?>" required></td>
                    <td>
                        <img src="<?php echo e($promo['imag']); ?>" alt="Promo" width="100">
                        <input form="<?php echo $fid; ?>" type="file" name="imagen" accept="image/*">
                    </td>
                    <td>
                        <!-- Un formulario por fila (va dentro de la celda para que el HTML sea válido) -->
                        <form id="<?php echo $fid; ?>" action="admin_promociones.php" method="POST" enctype="multipart/form-data" class="form-inline">
                            <input type="hidden" name="id_promocion" value="<?php echo (int) $promo['id_promocion']; ?>">
                            <button type="submit" name="accion" value="guardar" class="login-btn">Guardar Cambios</button>
                            <button type="submit" name="accion" value="eliminar" class="btn-del" formnovalidate
                                    onclick="return confirm('¿Eliminar esta promoción?');">Eliminar</button>
                        </form>
                    </td>
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
