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

$titulo = 'Promociones';
$seccion = 'promociones';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Promociones</h1>
                <p>Las promociones aparecen en el carrusel de la página de inicio.</p>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <p class="aviso <?php echo strpos($mensaje, 'Error') === 0 ? 'mal' : 'ok'; ?>"><?php echo e($mensaje); ?></p>
        <?php endif; ?>

        <section class="tarjeta admin-seccion">
            <h2>Nueva promoción</h2>
            <form action="admin_promociones.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar">
                <div class="form-grid">
                    <div class="campo campo-ancho">
                        <label for="nombre_promocion">Nombre de la promoción</label>
                        <input type="text" id="nombre_promocion" name="nombre_promocion" maxlength="50" required placeholder="Ej. Fundas 2x1">
                    </div>
                    <div class="campo">
                        <label for="precio_original">Precio original <small>(se muestra tachado)</small></label>
                        <input type="number" id="precio_original" step="0.01" min="0" name="precio_original" required>
                    </div>
                    <div class="campo">
                        <label for="precio_descuento">Precio con descuento <small>(el que se cobra)</small></label>
                        <input type="number" id="precio_descuento" step="0.01" min="0" name="precio_descuento" required>
                    </div>
                    <div class="campo campo-ancho">
                        <label for="imagen">Imagen</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" required>
                    </div>
                </div>
                <div class="form-acciones">
                    <button type="submit" class="btn btn-primario">Agregar promoción</button>
                </div>
            </form>
        </section>

        <section class="tarjeta admin-seccion">
            <h2>Promociones actuales</h2>
            <?php if (!$promociones): ?>
                <p class="vacio">Todavía no hay promociones.</p>
            <?php else: ?>
            <p class="ayuda">Cambia los datos directamente en la tabla y presiona <strong>Guardar</strong> en esa fila.</p>
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio original</th>
                            <th>Precio con descuento</th>
                            <th>Cambiar imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($promociones as $promo): $fid = 'promo-' . (int) $promo['id_promocion']; ?>
                        <tr>
                            <td><img src="<?php echo e($promo['imag']); ?>" alt="<?php echo e($promo['nombre_promocion']); ?>"></td>
                            <td><input form="<?php echo $fid; ?>" type="text" name="nombre_promocion" maxlength="50" value="<?php echo e($promo['nombre_promocion']); ?>" required aria-label="Nombre"></td>
                            <td><input form="<?php echo $fid; ?>" type="number" step="0.01" min="0" name="precio_original" value="<?php echo e($promo['precio_original']); ?>" required aria-label="Precio original"></td>
                            <td><input form="<?php echo $fid; ?>" type="number" step="0.01" min="0" name="precio_descuento" value="<?php echo e($promo['precio_descuento']); ?>" required aria-label="Precio con descuento"></td>
                            <td><input form="<?php echo $fid; ?>" type="file" name="imagen" accept="image/*" aria-label="Nueva imagen"></td>
                            <td>
                                <!-- Un formulario por fila (va dentro de la celda para que el HTML sea válido) -->
                                <form id="<?php echo $fid; ?>" action="admin_promociones.php" method="POST" enctype="multipart/form-data" class="tabla-acciones">
                                    <input type="hidden" name="id_promocion" value="<?php echo (int) $promo['id_promocion']; ?>">
                                    <button type="submit" name="accion" value="guardar" class="btn btn-suave btn-chico">Guardar</button>
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-peligro btn-chico" formnovalidate
                                            onclick="return confirm('¿Eliminar esta promoción?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>
<?php
require 'admin_pie.php';
