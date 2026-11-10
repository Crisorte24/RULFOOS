<?php
// Pie común de la tienda: datos de contacto, botones flotantes y scripts.
// Cada página puede definir antes:
//   $scripts_pie -> (opcional) HTML con los <script> propios de esa página
$scripts_pie = $scripts_pie ?? '';
?>
    </main>

    <footer class="pie">
        <div class="contenedor-ancho pie-columnas">
            <div>
                <a href="inicio rulfoos.php" class="logo">RULFOOS<span>Accesorios</span></a>
                <p>Accesorios para tu celular, audífonos, relojes y reparación de teléfonos. Mira nuestros productos en 3D y realidad aumentada.</p>
            </div>
            <div>
                <h3>Tienda</h3>
                <ul>
                    <li><a href="productos rulfoos php.php">Productos</a></li>
                    <li><a href="servicios rulfoos php.php">Servicios</a></li>
                    <li><a href="menu3d.php">Menú 3D</a></li>
                    <li><a href="contacto rulfos php.php">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h3>Contacto</h3>
                <ul class="pie-contacto">
                    <li><i class="fa-solid fa-phone"></i><a href="tel:+527294697480">+52 729 469 7480</a></li>
                    <li><i class="fa-solid fa-envelope"></i><a href="mailto:rulfooselectronica@gmail.com">rulfooselectronica@gmail.com</a></li>
                    <li><i class="fa-solid fa-location-dot"></i><span>Manzana 020, 50940 San Francisco Tlalcilalcalpan, Méx.</span></li>
                </ul>
            </div>
        </div>
        <div class="contenedor-ancho pie-final">
            <span>&copy; <?= date('Y') ?> RULFOOS Accesorios. Todos los derechos reservados.</span>
            <a href="login.php">Acceso administrador</a>
        </div>
    </footer>

    <!-- Botones flotantes -->
    <a href="https://wa.me/527294697480" class="btn-wsp" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>
    <button type="button" class="btn-vz" onclick="startVoiceNavigation()" title="Navegación por voz" aria-label="Navegación por voz">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 14a3 3 0 0 0 3-3V5a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zm5-3a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.92V21h2v-3.08A7 7 0 0 0 19 11h-2z"/></svg>
    </button>

    <!-- Ventana del código QR -->
    <div id="qrModal" class="modal" role="dialog" aria-modal="true" aria-label="Código QR">
        <div class="modal-contenido">
            <button type="button" class="modal-cerrar" onclick="cerrarQR()" aria-label="Cerrar">&times;</button>
            <img id="qrImage" alt="Código QR del producto">
            <p>Escanea el código con tu celular para ver el producto en realidad aumentada.</p>
        </div>
    </div>

    <script src="js/carrito.js"></script>
    <script src="js/tienda.js"></script>
    <script src="js/voz.js"></script>
    <?= $scripts_pie ?>
</body>
</html>
