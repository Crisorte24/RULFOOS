<?php
require 'conexion.php';
require 'paypal.php'; // llaves de PayPal (en config.php)

$productos = $conn->query('SELECT * FROM productos ORDER BY id')->fetch_all(MYSQLI_ASSOC);
$conn->close();

$titulo = 'Productos';
$pagina = 'productos';
require 'encabezado.php';
?>

        <section class="seccion">
            <div class="contenedor-ancho">
                <div class="seccion-titulo">
                    <h1>Productos</h1>
                    <p>Audífonos, fundas, relojes y más. Revisa tu carrito antes de pagar.</p>
                </div>

                <div class="zona-compra">
                    <!-- Lista de productos -->
                    <div class="productos-grid contenedor-items">
                        <?php foreach ($productos as $row):
                            $stock  = (int) $row['cantidad'];
                            $normal = (int) $row['precio1'];   // precio normal (tachado)
                            $venta  = (int) $row['precio2'];   // precio de venta (el que se cobra)
                        ?>
                        <article class="tarjeta item<?= $stock < 1 ? ' item-agotado' : '' ?>" id="producto-<?= (int) $row['id'] ?>"
                                 data-id="<?= (int) $row['id'] ?>"
                                 data-titulo="<?= e($row['nombre']) ?>"
                                 data-precio="<?= $venta ?>"
                                 data-imagen="<?= e($row['imagen']) ?>"
                                 data-stock="<?= $stock ?>">
                            <div class="imagen-caja">
                                <img src="<?= e($row['imagen']) ?>" alt="<?= e($row['nombre']) ?>" class="img-item" loading="lazy">
                            </div>
                            <span class="titulo-item"><?= e($row['nombre']) ?></span>
                            <div class="precios">
                                <?php if ($normal > $venta): ?>
                                    <span class="precio-anterior">$<?= number_format($normal, 2) ?></span>
                                <?php endif; ?>
                                <span class="precio-actual precio-item">$<?= number_format($venta, 2) ?></span>
                            </div>

                            <?php if ($stock < 1): ?>
                                <span class="stock-item agotado">Agotado</span>
                            <?php elseif ($stock <= 3): ?>
                                <span class="stock-item pocos">¡Solo quedan <?= $stock ?>!</span>
                            <?php else: ?>
                                <span class="stock-item">Disponibles: <?= $stock ?></span>
                            <?php endif; ?>

                            <?php if (!empty($row['url_3d']) || !empty($row['imagen_qr'])): ?>
                            <div class="item-acciones">
                                <?php if (!empty($row['url_3d'])): ?>
                                    <a href="<?= e($row['url_3d']) ?>" class="btn btn-secundario btn-chico" target="_blank" rel="noopener">Ver 3D</a>
                                <?php endif; ?>
                                <?php if (!empty($row['imagen_qr'])): ?>
                                    <button type="button" class="btn btn-suave btn-chico" data-qr="<?= e($row['imagen_qr']) ?>" onclick="mostrarQR(this.dataset.qr)">
                                        <i class="fa-solid fa-qrcode" aria-hidden="true"></i> QR
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if ($stock < 1): ?>
                                <button type="button" class="btn btn-primario btn-bloque boton-item" disabled>Agotado</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-primario btn-bloque boton-item">Agregar al carrito</button>
                            <?php endif; ?>
                        </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Carrito de pago: es el mismo carrito del ícono del menú (ver carrito.js) -->
                    <aside class="tarjeta carrito" id="carrito-pago" aria-label="Tu carrito">
                        <div class="header-carrito">
                            <h2>Tu carrito</h2>
                        </div>
                        <div class="carrito-items"></div>
                        <div class="carrito-total">
                            <div class="fila">
                                <strong>Total</strong>
                                <span class="carrito-precio-total">$0.00</span>
                            </div>
                            <button type="button" class="btn btn-oscuro btn-bloque btn-pagar">
                                Pagar <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div id="paypal-button-container"></div>
                    </aside>
                </div>
            </div>
        </section>

<?php
$scripts_pie = '';
if (paypalConfigurado()) {
    $scripts_pie .= '<script src="https://www.paypal.com/sdk/js?client-id=' . urlencode(PAYPAL_CLIENT_ID) . '&currency=' . PAYPAL_MONEDA . '"></script>';
}
$scripts_pie .= '<script src="js/productos.js"></script>';
require 'pie.php';
