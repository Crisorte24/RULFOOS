<?php
require 'conexion.php'; // para la función e() del encabezado

$estado = $_GET['estado'] ?? '';

$titulo = 'Contacto';
$pagina = 'contacto';
require 'encabezado.php';
?>

        <section class="seccion">
            <div class="contenedor-ancho">
                <div class="seccion-titulo">
                    <h1>Contáctanos</h1>
                    <p>¿Tienes dudas sobre un producto o una reparación? Escríbenos y te respondemos lo antes posible.</p>
                </div>

                <div class="contacto-grid">
                    <div class="tarjeta">
                        <h2 class="tarjeta-titulo">Visítanos o llámanos</h2>
                        <ul class="contacto-datos">
                            <li>
                                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                                <div><strong>Teléfono y WhatsApp</strong><a href="tel:+527294697480">+52 729 469 7480</a></div>
                            </li>
                            <li>
                                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                                <div><strong>Correo</strong><a href="mailto:rulfooselectronica@gmail.com">rulfooselectronica@gmail.com</a></div>
                            </li>
                            <li>
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                <div><strong>Dirección</strong><span>Manzana 020, 50940 San Francisco Tlalcilalcalpan, Méx.</span></div>
                            </li>
                        </ul>
                        <iframe class="mapa" title="Ubicación de RULFOOS en Google Maps"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1882.8321324035296!2d-99.76847450018002!3d19.296961063316537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cd8782869ccba5%3A0xcac7a375fc8087c9!2sHidalgo%2028%2C%20San%20Francisco%20Tlalcilalcalpan%2C%2050940%20San%20Francisco%20Tlalcilalcalpan%2C%20M%C3%A9x.!5e0!3m2!1ses-419!2smx!4v1728507111324!5m2!1ses-419!2smx"
                                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <form class="tarjeta" action="registrar.php" method="POST">
                        <h2 class="tarjeta-titulo">Envíanos un mensaje</h2>

                        <?php if ($estado === 'enviado'): ?>
                            <p class="aviso ok">¡Gracias! Recibimos tu mensaje y te responderemos pronto.</p>
                        <?php elseif ($estado === 'error'): ?>
                            <p class="aviso mal">Revisa tu nombre y correo e inténtalo de nuevo.</p>
                        <?php endif; ?>

                        <div class="campo">
                            <label for="c-nombre">Nombre y apellido</label>
                            <input type="text" id="c-nombre" name="name" maxlength="100" required autocomplete="name">
                        </div>
                        <div class="campo">
                            <label for="c-email">Correo electrónico</label>
                            <input type="email" id="c-email" name="email" maxlength="150" required autocomplete="email">
                        </div>
                        <div class="campo">
                            <label for="c-asunto">Asunto <small>(opcional)</small></label>
                            <input type="text" id="c-asunto" name="asunto" maxlength="150">
                        </div>
                        <div class="campo">
                            <label for="c-mensaje">Mensaje</label>
                            <textarea id="c-mensaje" name="mesage" placeholder="¿En qué te podemos ayudar?"></textarea>
                        </div>
                        <button type="submit" name="enviar" class="btn btn-primario btn-bloque">
                            Enviar mensaje <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>

<?php
require 'pie.php';
