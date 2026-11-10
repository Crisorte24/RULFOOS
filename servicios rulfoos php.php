<?php
require 'conexion.php'; // para la función e() del encabezado

$servicios = [
    [
        'imagen' => 'img servc/cambio display.jpg',
        'titulo' => 'Cambio de display',
        'texto'  => 'Reemplazamos pantallas dañadas o rotas para cualquier marca y modelo de teléfono. Servicio rápido y profesional, con piezas de alta calidad para dejar tu dispositivo como nuevo.',
        'video'  => 'https://www.youtube.com/watch?v=Cm6hs7ixifs',
    ],
    [
        'imagen' => 'img servc/cambio de bateria.jpg',
        'titulo' => 'Cambio de batería',
        'texto'  => 'Si tu teléfono ya no aguanta la carga o se apaga de repente, cambiamos la batería para smartphones de todas las marcas y te devolvemos la autonomía de fábrica.',
        'video'  => 'https://www.youtube.com/watch?v=seaswVi7OvA',
    ],
    [
        'imagen' => 'img servc/cambio portcar.png',
        'titulo' => 'Reparación de puerto de carga',
        'texto'  => 'Si tu celular no carga bien, reparamos o sustituimos el puerto de carga con herramientas especializadas para que vuelva a funcionar correctamente y de forma segura.',
        'video'  => 'https://www.youtube.com/watch?v=y4pMALF8Wms',
    ],
];

$titulo = 'Servicios';
$pagina = 'servicios';
require 'encabezado.php';
?>

        <section class="seccion">
            <div class="contenedor-ancho">
                <div class="seccion-titulo">
                    <h1>Nuestros servicios</h1>
                    <p>Reparamos tu celular con piezas de calidad. Pregunta por tu modelo sin compromiso.</p>
                </div>

                <div class="servicios-grid">
                    <?php foreach ($servicios as $s): ?>
                    <article class="tarjeta servicio">
                        <figure>
                            <img src="<?= e($s['imagen']) ?>" alt="<?= e($s['titulo']) ?>" loading="lazy">
                        </figure>
                        <div class="servicio-cuerpo">
                            <h3><?= e($s['titulo']) ?></h3>
                            <p><?= e($s['texto']) ?></p>
                            <a href="<?= e($s['video']) ?>" class="btn btn-secundario btn-chico" target="_blank" rel="noopener">
                                <i class="fa-brands fa-youtube" aria-hidden="true"></i> Ver video
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <div class="seccion-cta">
                    <a href="contacto rulfos php.php" class="btn btn-primario">Cotiza tu reparación</a>
                </div>
            </div>
        </section>

<?php
require 'pie.php';
