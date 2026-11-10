<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gool";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos");

// Obtener promociones
function obtenerPromociones($conn) {
    $sql = "SELECT * FROM promociones";
    $resultado = mysqli_query($conn, $sql);
    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}

$promociones = obtenerPromociones($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="estilo pag rulfoos php.css">
    <link rel="stylesheet" href="estilo pagar rulfoos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="as_voz_estilo.css">
</head>
<body>
    
        <header>
        <div class="menu container">
            <a href="#" class="logo">RULFOOS</a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt=""></label>
            <nav class="navbar">
                <ul>
                    <li><a href="inicio rulfoos.php">Inicio</a></li>
                    <li><a href="Servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="Productos rulfoos php.php">Productos</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                    
                </ul>
            </nav>
            <div>
                <ul>
                    <li class="submenu">
                        <img src="images/car.svg" id="img-carrito" alt="Carrito">
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead><th></th></thead>
                                <tbody></tbody>
                            </table>
                            <a href="#" id="vaciar-carrito" class="btn-3">Vaciar Carrito</a>
                            <li><a href="carrito rulfoos php.php" class="btn-4">Ir a pagar</a></li>
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
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="item">
                        <span class="titulo-item"><?php echo $row['nombre']; ?></span>
                        <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="img-item">
                        <span class="precio-item">$<?php echo $row['precio1']; ?></span>

                        <?php if (!empty($row['url_3d'])): ?>
                            <a href="<?= htmlspecialchars($row['url_3d']) ?>" class="btn-1" target="_blank">Ver 3D</a>
                        <?php endif; ?>

                        <?php if (!empty($row['imagen_qr'])): ?>
                            <button class="btn-qr" onclick="mostrarQR('<?= htmlspecialchars($row['imagen_qr']) ?>')">QR</button>
                        <?php endif; ?>

                        <button class="boton-item">Agregar al Carrito</button>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Carrito -->
            <div class="carrito" id="carrito">
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
<div id="paypal-button-container" style="display: none; margin-top: 20px;"></div>

            </div>
        </section>
    </section>

    <!-- Modal QR -->
    <div id="qrModal" class="modal">
        <span class="close" onclick="cerrarQR()">&times;</span>
        <img class="modal-content" id="qrImage" alt="Código QR">
    </div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="funciones java php.js"></script>
    <script src="pagar rulfoos.js"></script>
    <script src="funcion asistente voz.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=USD"></script>

    <script>
    let paypalRenderizado = false;

    function mostrarQR(imagenQR) {
        const modal = document.getElementById("qrModal");
        const qrImage = document.getElementById("qrImage");
        qrImage.src = imagenQR;
        modal.style.display = "block";
    }

    function cerrarQR() {
        document.getElementById("qrModal").style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("qrModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }

    function obtenerTotalCarrito() {
        const totalTexto = document.querySelector('.carrito-precio-total').textContent.trim();
        const totalNumerico = parseFloat(totalTexto.replace('$', '').replace(',', '').trim());
        return totalNumerico.toFixed(2);
    }

    document.querySelector('.btn-pagar').addEventListener('click', function() {
        const paypalContainer = document.getElementById('paypal-button-container');
        paypalContainer.style.display = 'block';

        if (!paypalRenderizado) {
            paypal.Buttons({
                style: {
                    shape: "rect",
                    color: "gold",
                    layout: "vertical",
                    label: "paypal"
                },
                createOrder: function (data, actions) {
                    const total = obtenerTotalCarrito();
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: total }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        alert("¡Transacción completada por " + details.payer.name.given_name + "!");
                        console.log("Detalles de la transacción:", details);
                    });
                },
                onError: function (err) {
                    console.error("Error en el pago:", err);
                    alert("Ocurrió un error al procesar el pago.");
                }
            }).render('#paypal-button-container');
            paypalRenderizado = true;
        }
    });
</script>

</body>

<!-- Footer -->
<footer class="footer-container">
    <div class="link">
        <p>&copy; 2024 Tienda de Accesorios RULFOOS</p>
    </div>
</footer>
</html>

