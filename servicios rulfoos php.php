<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="css/tienda.css">
    <link rel="stylesheet" href="css/servicios.css">
    <link rel="stylesheet" href="css/voz.css">
</head>
<body>
    <a href="https://wa.me/527294697480" class="btn-wsp" target="_blank" rel="noopener">
        <i class="fa fa-whatsapp icono"></i>
    </a>
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
    </header>

    <section class="servicios-container">
        <h2>Nuestros Servicios</h2>
        <div class="container-card">

            <div class="card">
                <figure>
                    <img src="img servc/cambio display.jpg" alt="Cambio de display">
                </figure>
                <div class="contenido-card">
                    <h3>Cambio de Display</h3>
                    <p>"Realizamos el reemplazo de pantallas dañadas o rotas para cualquier marca y modelo de teléfono móvil.
                        Garantizamos un servicio rápido y profesional, utilizando piezas de alta calidad para devolver tu dispositivo a
                        su estado original."</p>
                    <a href="https://www.youtube.com/watch?v=Cm6hs7ixifs" class="btn-3" target="_blank" rel="noopener">Video</a>
                </div>
            </div>

            <div class="card">
                <figure>
                    <img src="img servc/cambio de bateria.jpg" alt="Cambio de batería">
                </figure>
                <div class="contenido-card">
                    <h3>Cambio de Batería</h3>
                    <p>"Ofrecemos cambio de baterías para smartphones de todas las marcas.
                        Si tu teléfono ya no mantiene la carga o se apaga repentinamente, nuestra solución rápida y efectiva te devolverá
                        la autonomía y rendimiento de fábrica."</p>
                    <a href="https://www.youtube.com/watch?v=seaswVi7OvA" class="btn-3" target="_blank" rel="noopener">Video</a>
                </div>
            </div>

            <div class="card">
                <figure>
                    <img src="img servc/cambio portcar.png" alt="Reparación de puerto de carga">
                </figure>
                <div class="contenido-card">
                    <h3>Reparación de Puertos de Carga</h3>
                    <p>"Si tu dispositivo móvil tiene problemas de carga, reparamos o sustituimos el puerto de carga para asegurar que vuelva
                        a funcionar correctamente. Utilizamos herramientas especializadas para garantizar una reparación segura."</p>
                    <a href="https://www.youtube.com/watch?v=y4pMALF8Wms" class="btn-3" target="_blank" rel="noopener">Video</a>
                </div>
            </div>

        </div>
    </section>

    <footer class="footer-container">
        <div class="link">
            <p>&copy; 2024 Tienda de Accesorios RULFOOS</p>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="js/carrito.js"></script>
    <script src="js/voz.js"></script>
</body>
</html>
