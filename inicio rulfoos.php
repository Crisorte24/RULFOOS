<?php
require 'conexion.php';

$productos   = $conn->query('SELECT * FROM productos ORDER BY id')->fetch_all(MYSQLI_ASSOC);
$promociones = $conn->query('SELECT * FROM promociones ORDER BY id_promocion')->fetch_all(MYSQLI_ASSOC);
$conn->close();

// Porcentaje de descuento entre el precio normal y el de venta
function descuento($normal, $venta)
{
    return ($normal > 0 && $venta < $normal) ? round(100 - $venta * 100 / $normal) : 0;
}

$titulo = 'Inicio';
$pagina = 'inicio';
$css_extra = ['https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css'];
require 'encabezado.php';
?>

        <!-- Carrusel de productos -->
        <section class="hero" aria-label="Productos destacados">
            <div class="contenedor-ancho">
                <div class="swiper mySwiper-1">
                    <div class="swiper-wrapper">
                        <?php foreach ($productos as $row): $desc = descuento($row['precio1'], $row['precio2']); ?>
                        <div class="swiper-slide">
                            <div class="hero-slide">
                                <div class="hero-texto">
                                    <?php if ($desc > 0): ?>
                                        <span class="etiqueta-descuento">-<?= $desc ?>% de descuento</span>
                                    <?php endif; ?>
                                    <h2><?= e($row['nombre']) ?></h2>
                                    <div class="precios">
                                        <?php if ($desc > 0): ?>
                                            <span class="precio-anterior">$<?= number_format($row['precio1'], 2) ?></span>
                                        <?php endif; ?>
                                        <span class="precio-actual">$<?= number_format($row['precio2'], 2) ?></span>
                                    </div>
                                    <div class="hero-acciones">
                                        <a href="productos rulfoos php.php#producto-<?= (int) $row['id'] ?>" class="btn btn-primario">Comprar</a>
                                        <?php if (!empty($row['url_3d'])): ?>
                                            <a href="<?= e($row['url_3d']) ?>" class="btn btn-secundario" target="_blank" rel="noopener">Realidad aumentada</a>
                                        <?php endif; ?>
                                        <?php if (!empty($row['imagen_qr'])): ?>
                                            <button type="button" class="btn btn-suave" data-qr="<?= e($row['imagen_qr']) ?>" onclick="mostrarQR(this.dataset.qr)">
                                                <i class="fa-solid fa-qrcode" aria-hidden="true"></i> QR
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="hero-imagen">
                                    <img src="<?= e($row['imagen']) ?>" alt="<?= e($row['nombre']) ?>">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>

        <!-- Promociones -->
        <section class="seccion" id="lista-1">
            <div class="contenedor-ancho">
                <div class="seccion-titulo">
                    <h2>Promociones</h2>
                    <p>Ofertas por tiempo limitado. Agrégalas a tu carrito y paga junto con tus productos.</p>
                </div>

                <div class="promos-carrusel">
                    <div class="swiper mySwiper-2">
                        <div class="swiper-wrapper">
                            <?php foreach ($promociones as $promo): ?>
                            <div class="swiper-slide">
                                <article class="tarjeta promo-tarjeta">
                                    <div class="imagen-caja">
                                        <img src="<?= e($promo['imag']) ?>" alt="<?= e($promo['nombre_promocion']) ?>" loading="lazy">
                                    </div>
                                    <h3><?= e($promo['nombre_promocion']) ?></h3>
                                    <div class="precios">
                                        <span class="precio-anterior">$<?= number_format($promo['precio_original'], 2) ?></span>
                                        <span class="precio-actual">$<?= number_format($promo['precio_descuento'], 2) ?></span>
                                    </div>
                                    <a href="#" class="agregar-carrito btn btn-primario btn-bloque"
                                       data-id="<?= (int) $promo['id_promocion'] ?>"
                                       data-titulo="<?= e($promo['nombre_promocion']) ?>"
                                       data-precio="<?= e($promo['precio_descuento']) ?>"
                                       data-imagen="<?= e($promo['imag']) ?>">Agregar al carrito</a>
                                </article>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>

<?php
$scripts_pie = '
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
    <script src="js/inicio.js"></script>
    <script src="js/chatbase.js"></script>
    <script src="https://www.chatbase.co/embed.min.js" chatbotId="y_-v-fRHWxd6fqH6TEsRT" domain="www.chatbase.co" defer></script>
';
require 'pie.php';
