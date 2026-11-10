<?php
require 'conexion.php';

$productos   = $conn->query('SELECT * FROM productos ORDER BY id')->fetch_all(MYSQLI_ASSOC);
$promociones = $conn->query('SELECT * FROM promociones ORDER BY id_promocion')->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RULFOOS PAG</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.css">
    <link rel="stylesheet" href="css/tienda.css">
    <link rel="stylesheet" href="css/voz.css">
</head>

<body>
    <a href="https://wa.me/527294697480" class="btn-wsp" target="_blank" rel="noopener">
        <i class="fa fa-whatsapp icono"></i>
    </a>
    <button class="btn-vz" onclick="startVoiceNavigation()" title="Navegación por voz">
        <i class="fa-solid fa-microphone"></i>
    </button>

    <header class="header">
        <div class="menu container">
            <a href="inicio rulfoos.php" class="logo">RULFOOS ACCESORIOS</a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt="Menú"></label>
            <nav class="navbar">
                <ul>
                    <li><a href="inicio rulfoos.php">Inicio</a></li>
                    <li><a href="servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="productos rulfoos php.php">Productos</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="menu3d.php">Menu 3D</a></li>
                    <li><a href="#" id="suggestion-box">Buscar</a></li>
                </ul>
            </nav>

            <div>
                <ul>
                    <li class="submenu">
                        <img src="images/car.svg" id="img-carrito" alt="Carrito">
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead>
                                    <tr><th></th></tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <p class="total-mini">Total: <span id="total-mini">$0.00</span></p>
                            <a href="#" id="vaciar-carrito" class="btn-3">Vaciar Carrito</a>
                            <a href="productos rulfoos php.php" class="btn-4">Ir a pagar</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="header-content container">
            <div class="swiper mySwiper-1">
                <div class="swiper-wrapper">
                    <?php foreach ($productos as $row): ?>
                    <div class="swiper-slide">
                        <div class="header-info">
                            <div class="header-txt">
                                <h1><?= e($row['nombre']) ?></h1>
                                <div class="prices">
                                    <p class="price-1">$<?= number_format($row['precio1'], 2) ?></p>
                                    <p class="price-2">$<?= number_format($row['precio2'], 2) ?></p>
                                </div>
                                <?php if (!empty($row['url_3d'])): ?>
                                    <a href="<?= e($row['url_3d']) ?>" class="btn-1" target="_blank" rel="noopener">Realidad aumentada</a>
                                <?php endif; ?>

                                <!-- Botón QR que abre el modal -->
                                <?php if (!empty($row['imagen_qr'])): ?>
                                    <button class="btn-qr" data-qr="<?= e($row['imagen_qr']) ?>" onclick="mostrarQR(this.dataset.qr)">QR</button>
                                <?php endif; ?>
                            </div>
                            <div class="header-img">
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
    </header>

    <hr>

    <section class="promos-container" id="lista-1">
        <h2>Promociones</h2>
        <div class="swiper mySwiper-2">
            <div class="swiper-wrapper">
                <?php foreach ($promociones as $promo): ?>
                <div class="swiper-slide">
                    <div class="categorie">
                        <div class="categoria-1">
                            <h3><?= e($promo['nombre_promocion']) ?></h3>
                            <div class="prices">
                                <p class="price-1">$<?= number_format($promo['precio_original'], 2) ?></p>
                                <p class="precio">$<?= number_format($promo['precio_descuento'], 2) ?></p>
                            </div>
                            <a href="#" class="agregar-carrito btn-3"
                               data-id="<?= (int) $promo['id_promocion'] ?>"
                               data-titulo="<?= e($promo['nombre_promocion']) ?>"
                               data-precio="<?= e($promo['precio_descuento']) ?>"
                               data-imagen="<?= e($promo['imag']) ?>">Agregar al carrito</a>
                        </div>
                        <div class="categoria-img">
                            <img src="<?= e($promo['imag']) ?>" alt="<?= e($promo['nombre_promocion']) ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-buttons">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>

    <hr>

    <!-- Panel del buscador (se abre con "Buscar" en el menú) -->
    <div id="suggestion-panel">
        <h3>Buscar Productos</h3>
        <input type="text" id="product-search" placeholder="Ingrese el nombre del producto">
        <ul id="suggestions-list"></ul>
    </div>

    <!-- Modal para mostrar el código QR -->
    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarQR()">&times;</span>
            <img id="qrImage" alt="Código QR">
        </div>
    </div>

    <footer class="footer-container">
        <div class="link">
            <p>&copy; 2024 Tienda de Accesorios RULFOOS</p>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.1.14/swiper-bundle.min.js"></script>
    <script src="js/carrito.js"></script>
    <script src="js/voz.js"></script>
    <script src="js/inicio.js"></script>

    <!-- Chatbot de Chatbase -->
    <script src="js/chatbase.js"></script>
    <script src="https://www.chatbase.co/embed.min.js" chatbotId="y_-v-fRHWxd6fqH6TEsRT" domain="www.chatbase.co" defer></script>
</body>
</html>
