<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="estilo pag rulfoos php.css">
   
    <link rel="stylesheet" href="servicios estilo.css">
    <link rel="stylesheet" href="as_voz_estilo.css">
</head>
<body>
        <a href="https://api.whatsapp.com/send?phone=7294697480" class="btn-wsp" target="_blank">
	    <i class="fa fa-whatsapp icono"></i>
	</a>
</body>
<body>
    <header>
        <div class="menu container">
            <a  href="#" class="logo">RULFOOS</a>
            <input type="checkbox" id="menu" />
            <label for="menu"><img src="images/menu.png" class="menu-icono" alt=""></label>
            <nav class="navbar">
                    <ul>
                        <li><a href="inicio rulfoos.php">Inicio</a></li>
                        <li><a href="Servicios rulfoos php.php">Servicios</a></li>
                        <li><a href="Productos rulfoos php.php" >Productos</a></li>
                        <li><a href="contacto rulfos php.php" >Contacto</a></li>
                        
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
                            <li><a href="carrito rulfoos php.php" class="btn-4" >Ir a pagar</a></li>
                        </div>
                    </li>
                </ul>
            </div>
    </header>
    <section class="servicios-container">
        <h2>Nuestros Servicios</h2>
        <div class="container-card">
	
    <div class="card">
        <figure>
            <img src="img servc/cambio display.jpg">
        </figure>
        <div class="contenido-card">
            <h3>Cambio de Display</h3>
            <p>"Realizamos el reemplazo de pantallas dañadas o rotas para cualquier marca y modelo de teléfono móvil.
                 Garantizamos un servicio rápido y profesional, utilizando piezas de alta calidad para devolver tu dispositivo a
                  su estado original."</p>
                  <a href="https://www.youtube.com/watch?v=Cm6hs7ixifs" id="vaciar-carrito" class="btn-3">Video</a>
            <!--<a href="#">Leer Màs</a>  -->
        </div>
    </div>
    <div class="card">
        <figure>
            <img src="img servc/cambio de bateria.jpg">
        </figure>
        <div class="contenido-card">
            <h3>Cambio de Batería</h3>
            <p>"Ofrecemos cambio de baterías para smartphones de todas las marcas. 
                Si tu teléfono ya no mantiene la carga o se apaga repentinamente, nuestra solución rápida y efectiva te devolverá 
                la autonomía y rendimiento de fábrica."</p>
                <a href="https://www.youtube.com/watch?v=seaswVi7OvA" id="vaciar-carrito" class="btn-3">Video</a>
            
             <!--<a href="#">Leer Màs</a>  -->
        </div>
    </div>
    <div class="card">
        <figure>
            <img src="img servc/cambio portcar.png">
        </figure>
        <div class="contenido-card">
            <h3>Reparación de Puertos de Carga</h3>
            <p>"Si tu dispositivo móvil tiene problemas de carga, reparamos o sustituimos el puerto de carga para asegurar que vuelva 
                a funcionar correctamente. Utilizamos herramientas especializadas para garantizar una reparación segura."</p>
                <a href="https://www.youtube.com/watch?v=y4pMALF8Wms" id="vaciar-carrito" class="btn-3">Video</a>
            </button>
            <!--<a href="#">Leer Màs</a>  -->
        </div>
    </div>
    </div>
               
        
    </section>

    <footer class="footer-container">

        <div class="link">
            <p> 
                &copy; 2024 Tienda de Accesorios RULFOOS
            </p>
       
       </div>
    
     </footer>


     <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="funciones java php.js" ></script>
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
        });

        const swiper2 = new Swiper('.mySwiper-2', {
            slidesPerView: 2,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true,
        });



</body>
</html>