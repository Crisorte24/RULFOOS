<html lang="en"></html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactanos</title>
    <link rel="stylesheet" href="estilo contacto rulfoos php.css">
    <link rel="stylesheet" href="estilo pag rulfoos php.css">
    <link rel="stylesheet" href="as_voz_estilo.css">
    <script src="https://kit.fontawesome.com/b408879b64.js" crossorigin="anonymous"></script>
    
</head>

<body>
        <a href="https://api.whatsapp.com/send?phone=7294697480" class="btn-wsp" target="_blank">
        <i class="fa fa-whatsapp icono"></i>
    </a>

    <style>
        .btn-wsp {
            position: fixed;
            width: 80px; /* Aumentado */
            height: 80px; /* Aumentado */
            line-height: 80px; /* Aumentado */
            bottom: 25px;
            right: 25px;
            background: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 45px; /* Aumentado */
            box-shadow: 0px 1px 10px rgba(0, 0, 0, 0.3);
            z-index: 100;
            transition: all 300ms ease;
        }

        .icono {
            color: #FFF;
            font-size: 45px; /* Ajustar tamaño del ícono */
            margin-top: 15px;
        }
    </style>
</body>
      
<div class="menu container">
    <a  href="#" class="logo">RULFOOS</a>
    <input type="checkbox" id="menu" />
    <label for="menu"><img src="images/menu.png" class="menu-icono" alt=""></label>
            <nav class="navbar">
                        <ul>
                            <li><a href="inicio rulfoos.php">Inicio</a></li>
                            <li><a href="Servicios rulfoos php.php" >Servicios</a></li>
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
<body>
    <div class="container">
        <div class="box-info">
            
            <h1>CONTÁCTATE CON NOSOTROS</h1>
            <div class="data">
                <p><i class="fa-solid fa-phone"></i> +52 7294697480</p>
                <p><i class="fa-solid fa-envelope"></i> rulfooselectronica@gmail.com</p>
                <p><i class="fa-solid fa-location-dot"></i> Manzana 020, 50940 San Francisco Tlalcilalcalpan, Méx.</p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1882.8321324035296!2d-99.76847450018002!3d19.296961063316537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd8782869ccba5%3A0xcac7a375fc8087c9!2sHidalgo%2028%2C%20San%20Francisco%20Tlalcilalcalpan%2C%2050940%20San%20Francisco%20Tlalcilalcalpan%2C%20M%C3%A9x.!5e0!3m2!1ses-419!2smx!4v1728507111324!5m2!1ses-419!2smx" width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            
                </div>
        </div>
                <form action="registrar.php" method="POST">
            <div class="input-box">
                <input type="text" name="name" placeholder="Nombre y apellido" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" required placeholder="Correo electrónico">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="text" name="asunto" placeholder="Asunto">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div class="input-box">
                <textarea name="mesage" placeholder="Escribe tu mensaje..."></textarea>
            </div>
            <button type="submit" name="enviar">Enviar mensaje</button>
        </form>

    </div>

    <?php
   include("registrar.php");
    ?>
  
  <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
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
    </script>



</body>
</html>