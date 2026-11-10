<?php
require 'conexion.php';

$productos = $conn->query('SELECT * FROM productos ORDER BY id')->fetch_all(MYSQLI_ASSOC);
$conn->close();

// Client ID de PayPal: créalo en https://developer.paypal.com (Apps & Credentials).
// Mientras diga TU_CLIENT_ID, el botón de pago avisará que falta configurarlo.
$PAYPAL_CLIENT_ID = 'TU_CLIENT_ID';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="css/tienda.css">
    <link rel="stylesheet" href="css/pago.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/voz.css">
</head>
<body>
    <button class="btn-vz" onclick="startVoiceNavigation()" title="Navegación por voz">
        <i class="fa-solid fa-microphone"></i>
    </button>

    <header>
        <div class="menu container">
            <a href="inicio rulfoos.php" class="logo">RULFOOS</a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt="Menú"></label>
            <nav class="navbar">
                <ul>
                    <li><a href="inicio rulfoos.php">Inicio</a></li>
                    <li><a href="servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="productos rulfoos php.php">Productos</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                </ul>
            </nav>
            <div>
                <ul>
                    <li class="submenu">
                        <img src="images/car.svg" id="img-carrito" alt="Carrito">
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead><tr><th></th></tr></thead>
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
    </header>

    <!-- Título -->
    <section class="servicios-container">
        <h2>Productos</h2>
        <p>Verifica tus artículos antes de proceder al pago para asegurarte de que tienes todo lo que necesitas.</p>
    </section>

    <!-- Lista de productos -->
    <section class="servicios-container">
        <section class="contenedor">
            <div class="contenedor-items">
                <?php foreach ($productos as $row): ?>
                    <div class="item" id="producto-<?= (int) $row['id'] ?>"
                         data-id="<?= (int) $row['id'] ?>"
                         data-titulo="<?= e($row['nombre']) ?>"
                         data-precio="<?= e($row['precio1']) ?>"
                         data-imagen="<?= e($row['imagen']) ?>">
                        <span class="titulo-item"><?= e($row['nombre']) ?></span>
                        <img src="<?= e($row['imagen']) ?>" alt="<?= e($row['nombre']) ?>" class="img-item">
                        <span class="precio-item">$<?= number_format($row['precio1'], 2) ?></span>

                        <?php if (!empty($row['url_3d'])): ?>
                            <a href="<?= e($row['url_3d']) ?>" class="btn-1" target="_blank" rel="noopener">Ver 3D</a>
                        <?php endif; ?>

                        <?php if (!empty($row['imagen_qr'])): ?>
                            <button class="btn-qr" data-qr="<?= e($row['imagen_qr']) ?>" onclick="mostrarQR(this.dataset.qr)">QR</button>
                        <?php endif; ?>

                        <button class="boton-item">Agregar al Carrito</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carrito de pago: muestra el mismo carrito que el ícono del menú (ver carrito.js) -->
            <div class="carrito" id="carrito-pago">
                <div class="header-carrito">
                    <h2>Tu Carrito</h2>
                </div>
                <div class="carrito-items"></div>
                <div class="carrito-total">
                    <div class="fila">
                        <strong class="ttl">Tu Total</strong>
                        <span class="carrito-precio-total">$0.00</span>
                    </div>
                    <button class="btn-pagar">Pagar <i class="fa-solid fa-bag-shopping"></i></button>
                </div>
                <!-- Botón de PayPal oculto inicialmente -->
                <div id="paypal-button-container"></div>
            </div>
        </section>
    </section>

    <!-- Modal QR -->
    <div id="qrModal" class="modal">
        <span class="close" onclick="cerrarQR()">&times;</span>
        <img class="modal-content" id="qrImage" alt="Código QR">
    </div>

    <footer class="footer-container">
        <div class="link">
            <p>&copy; 2024 Tienda de Accesorios RULFOOS</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="js/carrito.js"></script>
    <script src="js/voz.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?= urlencode($PAYPAL_CLIENT_ID) ?>&currency=MXN"></script>

    <script src="js/productos.js"></script>
</body>
</html>
