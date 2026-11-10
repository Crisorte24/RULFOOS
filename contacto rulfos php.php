<?php
$estado = $_GET['estado'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos</title>
    <link rel="stylesheet" href="css/contacto.css">
    <link rel="stylesheet" href="css/tienda.css">
    <link rel="stylesheet" href="css/voz.css">
    <script src="https://kit.fontawesome.com/b408879b64.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/contacto-extra.css">
</head>

<body>
    <a href="https://wa.me/527294697480" class="btn-wsp" target="_blank" rel="noopener">
        <i class="fa fa-whatsapp icono"></i>
    </a>
    <button class="btn-vz" onclick="startVoiceNavigation()" title="Navegación por voz">
        <i class="fa-solid fa-microphone"></i>
    </button>

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
            <?php if ($estado === 'enviado'): ?>
                <p class="aviso ok">¡Gracias! Recibimos tu mensaje y te responderemos pronto.</p>
            <?php elseif ($estado === 'error'): ?>
                <p class="aviso mal">Revisa tu nombre y correo e inténtalo de nuevo.</p>
            <?php endif; ?>
            <div class="input-box">
                <input type="text" name="name" placeholder="Nombre y apellido" maxlength="100" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" required placeholder="Correo electrónico" maxlength="150">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="text" name="asunto" placeholder="Asunto" maxlength="150">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div class="input-box">
                <textarea name="mesage" placeholder="Escribe tu mensaje..."></textarea>
            </div>
            <button type="submit" name="enviar">Enviar mensaje</button>
        </form>
    </div>

    <script src="js/carrito.js"></script>
    <script src="js/voz.js"></script>
</body>
</html>
