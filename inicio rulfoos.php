<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "gool");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos");

// Función para obtener todas las promociones
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
    <title>RULFOOS PAG</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="estilo pag rulfoos php.css">
    <link rel="stylesheet" href="as_voz_estilo.css">
    
</head>

<body>
        

    <a href="https://api.whatsapp.com/send?phone=7294697480" class="btn-wsp" target="_blank">
        <i class="fa fa-whatsapp icono"></i>
    </a>

    <header class="header">
        <div class="menu container">
            <a href="#" class="logo">RULFOOS ACCESORIOS </a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt=""></label>
            <nav class="navbar">
                <ul>
                    <li><a href="inicio rulfoos.php">Inicio</a></li>
                    <li><a href="Servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="productos rulfoos php.php">Productos</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="menu3d.php">Menu 3D</a></li> 
                    

                    

                   
                </ul>
            </nav>

            <div>
                <ul>
                    <li class="submenu">
                        <img src="images/car.svg" id="img-carrito" alt="">
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a href="#" id="vaciar-carrito" class="btn-3">Vaciar Carrito</a>
                            <li><a href="carrito rulfoos php.php" class="btn-4">Ir a pagar</a></li>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="header-content container">
            <div class="swiper mySwiper-1">
                <div class="swiper-wrapper">
                <?php while ($row = $result->fetch_assoc()): ?>
<div class="swiper-slide">
    <div class="header-info">
        <div class="header-txt">
            <h1><?= htmlspecialchars($row['nombre']) ?></h1>
            <div class="prices">
                <p class="price-1">$<?= number_format($row['precio1'], 2) ?></p>
                <p class="price-2">$<?= number_format($row['precio2'], 2) ?></p>
            </div>
            <?php if (!empty($row['url_3d'])): ?>
                <a href="<?= htmlspecialchars($row['url_3d']) ?>" class="btn-1" target="_blank">Realidad aumentada</a>
            <?php endif; ?>
            
            <!-- Botón QR que abre el modal -->
            <?php if (!empty($row['imagen_qr'])): ?>
                <button class="btn-qr" onclick="mostrarQR('<?= htmlspecialchars($row['imagen_qr']) ?>')">QR</button>
                
            <?php endif; ?>

            
        </div>
        <div class="header-img">
            <img src="<?= htmlspecialchars($row['imagen']) ?>" alt="">
        </div>
       
    </div>
</div>
<?php endwhile; ?>

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
                            <h3><?php echo htmlspecialchars($promo['nombre_promocion']); ?></h3>
                            <div class="prices">
                                <p class="price-1">$<?php echo number_format($promo['precio_original'], 2); ?></p>
                                <p class="precio">$<?php echo number_format($promo['precio_descuento'], 2); ?></p>
                            </div>
                            <a href="#" class="agregar-carrito btn-3" data-id="<?php echo htmlspecialchars($promo['id_promocion']); ?>">Agregar al carrito</a>
                        </div>
                        <div class="categoria-img">
                            <img src="<?php echo htmlspecialchars($promo['imag']); ?>" alt="<?php echo htmlspecialchars($promo['nombre_promocion']); ?>">
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



    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="funciones java php.js"></script>
    <script src="funcion asistente voz.js"></script>
    <script>
    const swiper1 = new Swiper('.mySwiper-1', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {        // Autoplay para avanzar automáticamente
            delay: 4000,   // Tiempo en milisegundos (3 segundos) antes de avanzar
            disableOnInteraction: false, // Continúa el autoplay después de la interacción
        },
        loop: true         // Loop para que el carrusel vuelva al inicio
    });

    const swiper2 = new Swiper('.mySwiper-2', {
        slidesPerView: 2,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {        // Autoplay para el segundo carrusel
            delay: 3000,   // Puedes ajustar el tiempo según tus preferencias
            disableOnInteraction: false,
        },
        loop: true
    });
</script>




    <div id="suggestion-panel" style="display: none; position: fixed; top: 80px; left: 20px; width: 300px; background-color: white; border: 1px solid #ddd; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 20px;">
        <h3>Buscar Productos</h3>
        <input type="text" id="product-search" placeholder="Ingrese el nombre del producto" style="width: 100%; padding: 8px; margin-bottom: 10px;">
        <ul id="suggestions-list" style="list-style-type: none; padding: 0;"></ul>
    </div>

    <script>
        // Mostrar/Ocultar el panel de sugerencias
        document.getElementById("suggestion-box").addEventListener("click", function() {
            const panel = document.getElementById("suggestion-panel");
            panel.style.display = panel.style.display === "none" || panel.style.display === "" ? "block" : "none";
        });

        // Buscar productos y mostrar sugerencias
        document.getElementById("product-search").addEventListener("input", function() {
            const query = this.value.toLowerCase();
            const suggestionsList = document.getElementById("suggestions-list");

            if (query.length > 0) {
                fetch('buscar_productos.php?q=' + query)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = ""; // Limpiar la lista de sugerencias

                        data.forEach(producto => {
                            const li = document.createElement("li");
                            
                            // Crear un enlace para cada producto
                            const link = document.createElement("a");
                            link.href = "http://localhost:8080/php%20pag%20rulfoos/carrito%20rulfoos%20php.php?id=" + producto.id; // Cambia 'producto.php' a la URL de tu producto
                            link.textContent = producto.nombre;
                            link.style.display = "block"; // Hacer que el enlace ocupe todo el espacio del li
                            link.style.padding = "5px 0"; // Añadir un poco de espacio alrededor
                            link.style.color = "#007bff"; // Color del enlace
                            link.style.textDecoration = "none"; // Sin subrayado

                            li.appendChild(link);
                            suggestionsList.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error("Error al obtener las sugerencias:", error);
                    });
            } else {
                suggestionsList.innerHTML = ""; // Limpiar la lista si no hay input
            }
        });
    </script> 
    
    <script>
    window.embeddedChatbotConfig = {
    chatbotId: "y_-v-fRHWxd6fqH6TEsRT",
    domain: "www.chatbase.co"
    }
    </script>
    <script
    src="https://www.chatbase.co/embed.min.js"
    chatbotId="y_-v-fRHWxd6fqH6TEsRT"
    domain="www.chatbase.co"
    defer>
    </script>

    
    
    <!--Modal para mostrar el código QR --> 
    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarQR()">&times;</span>
            <img id="qrImage" alt="Código QR">
        </div>
    </div>




<script>
    function mostrarQR(src) {
    var modal = document.getElementById("qrModal");
    var qrImage = document.getElementById("qrImage");
    qrImage.src = src; // Cambia la imagen del QR dinámicamente
    modal.style.display = "block"; // Muestra la mini ventana
}

function cerrarQR() {
    document.getElementById("qrModal").style.display = "none"; // Oculta la mini ventana
}
</script>



<script>
    function mostrarAvatar() {
        const avatarBox = document.getElementById("avatar-box");
        avatarBox.style.display = "block";
    }

    function cerrarAvatar() {
        const avatarBox = document.getElementById("avatar-box");
        avatarBox.style.display = "none";
    }
    </script>
</body>



<footer class="footer-container">
        <div class="link">
            <p>&copy; 2024 Tienda de Accesorios RULFOOS</p>
        </div>
    </footer>
</html>
