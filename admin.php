<?php
require 'auth.php';          // Solo administradores con sesión.
require 'conexion.php';
require 'subir_imagen.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int) ($_POST['id'] ?? 0);           // 0 = producto nuevo
    $nombre    = trim($_POST['nombre'] ?? '');
    $precio1   = max(0, (int) ($_POST['precio1'] ?? 0));
    $precio2   = max(0, (int) ($_POST['precio2'] ?? 0));
    $cantidad  = max(0, (int) ($_POST['cantidad'] ?? 0)); // el inventario nunca es negativo
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

$titulo = 'Productos';
$seccion = 'productos';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Productos</h1>
                <p>Agrega, edita y revisa el inventario de la tienda.</p>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <p class="aviso <?php echo strpos($mensaje, 'Error') === 0 ? 'mal' : 'ok'; ?>"><?php echo e($mensaje); ?></p>
        <?php endif; ?>

        <section class="tarjeta admin-seccion">
            <h2 id="titulo-form">Nuevo producto</h2>
            <form method="POST" enctype="multipart/form-data" id="form-producto">
                <input type="hidden" name="id" value="">
                <div class="form-grid">
                    <div class="campo campo-ancho">
                        <label for="p-nombre">Nombre</label>
                        <input type="text" id="p-nombre" name="nombre" maxlength="50" required placeholder="Ej. Airpods Pro 2">
                    </div>
                    <div class="campo">
                        <label for="p-precio1">Precio normal <small>(se muestra tachado)</small></label>
                        <input type="number" id="p-precio1" name="precio1" min="0" step="1" required placeholder="Ej. 2200">
                    </div>
                    <div class="campo">
                        <label for="p-precio2">Precio de venta <small>(el que se cobra)</small></label>
                        <input type="number" id="p-precio2" name="precio2" min="0" step="1" required placeholder="Ej. 1999">
                    </div>
                    <div class="campo">
                        <label for="p-cantidad">Piezas en inventario</label>
                        <input type="number" id="p-cantidad" name="cantidad" min="0" step="1" required placeholder="Ej. 10">
                    </div>
                    <div class="campo">
                        <label for="p-url">Enlace de realidad aumentada <small>(opcional)</small></label>
                        <input type="url" id="p-url" name="url_3d" placeholder="https://mywebar.com/p/...">
                    </div>
                    <div class="campo">
                        <label for="p-imagen">Foto del producto <small id="nota-imagen"></small></label>
                        <input type="file" id="p-imagen" name="imagen" accept="image/*" required>
                    </div>
                    <div class="campo">
                        <label for="p-qr">Imagen del código QR <small>(opcional)</small></label>
                        <input type="file" id="p-qr" name="imagen_qr" accept="image/*">
                    </div>
                </div>
                <div class="form-acciones">
                    <button type="submit" class="btn btn-primario">Guardar producto</button>
                    <button type="button" class="btn btn-secundario" id="cancelar-edicion" hidden>Cancelar edición</button>
                </div>
            </form>
        </section>

        <section class="tarjeta admin-seccion">
            <h2>Lista de productos</h2>
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th class="num">Precio normal</th>
                            <th class="num">Precio de venta</th>
                            <th>Inventario</th>
                            <th>QR</th>
                            <th>Realidad aumentada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): $stock = (int) $row['cantidad']; ?>
                        <tr>
                            <td><img src="<?php echo e($row['imagen']); ?>" alt="<?php echo e($row['nombre']); ?>"></td>
                            <td><strong><?php echo e($row['nombre']); ?></strong><br><small class="ayuda">ID <?php echo (int) $row['id']; ?></small></td>
                            <td class="num">$<?php echo number_format($row['precio1'], 2); ?></td>
                            <td class="num"><strong>$<?php echo number_format($row['precio2'], 2); ?></strong></td>
                            <td>
                                <?php if ($stock < 1): ?>
                                    <span class="estado peligro">Agotado</span>
                                <?php elseif ($stock <= 3): ?>
                                    <span class="estado alerta"><?php echo $stock; ?> piezas</span>
                                <?php else: ?>
                                    <span class="estado ok"><?php echo $stock; ?> piezas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['imagen_qr'])): ?>
                                    <img src="<?php echo e($row['imagen_qr']); ?>" alt="QR de <?php echo e($row['nombre']); ?>">
                                <?php else: ?>
                                    <span class="ayuda">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row['url_3d'])): ?>
                                    <a href="<?php echo e($row['url_3d']); ?>" class="enlace-corto" target="_blank" rel="noopener" title="<?php echo e($row['url_3d']); ?>"><?php echo e(preg_replace('#^https?://#', '', $row['url_3d'])); ?></a>
                                <?php else: ?>
                                    <span class="ayuda">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="tabla-acciones">
                                    <button type="button" class="btn btn-suave btn-chico btn-editar"
                                            data-id="<?php echo (int) $row['id']; ?>"
                                            data-nombre="<?php echo e($row['nombre']); ?>"
                                            data-precio1="<?php echo e($row['precio1']); ?>"
                                            data-precio2="<?php echo e($row['precio2']); ?>"
                                            data-cantidad="<?php echo e($row['cantidad']); ?>"
                                            data-url3d="<?php echo e($row['url_3d']); ?>">Editar</button>
                                    <form method="POST" action="delete.php" class="form-inline"
                                          onsubmit="return confirm('¿Eliminar este producto?');">
                                        <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                                        <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php
$scripts_pie = '<script src="js/admin.js"></script>';
require 'admin_pie.php';
