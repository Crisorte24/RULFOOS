<?php
require 'conexion.php'; // para la función e() del encabezado

// Modelos 3D publicados en Sketchfab (cuenta: crisorte)
$modelos = [
    ['nombre' => 'Huawei Freebuds Pro 3', 'embed' => 'https://sketchfab.com/models/3a70da1798e44bb9a383dcf666bb69b7/embed', 'enlace' => 'https://sketchfab.com/3d-models/hauwei-freebuds-pro-3-3a70da1798e44bb9a383dcf666bb69b7?utm_medium=embed&utm_campaign=share-popup&utm_content=3a70da1798e44bb9a383dcf666bb69b7'],
    ['nombre' => 'Airpods Pro 2', 'embed' => 'https://sketchfab.com/models/008a88e800cf4bff8ad0d20e99f5c81f/embed', 'enlace' => 'https://sketchfab.com/3d-models/airpods-pro-2-008a88e800cf4bff8ad0d20e99f5c81f?utm_medium=embed&utm_campaign=share-popup&utm_content=008a88e800cf4bff8ad0d20e99f5c81f'],
    ['nombre' => 'Samsung Buds Pro 3', 'embed' => 'https://sketchfab.com/models/aba9c22695414d75a74a3589d9885653/embed', 'enlace' => 'https://sketchfab.com/3d-models/samsung-buds-pro-3-aba9c22695414d75a74a3589d9885653?utm_medium=embed&utm_campaign=share-popup&utm_content=aba9c22695414d75a74a3589d9885653'],
    ['nombre' => 'Fundas Pareja', 'embed' => 'https://sketchfab.com/models/3ac68cd279194bd583aea40b39e2769a/embed', 'enlace' => 'https://sketchfab.com/3d-models/fundas-pareja-3ac68cd279194bd583aea40b39e2769a?utm_medium=embed&utm_campaign=share-popup&utm_content=3ac68cd279194bd583aea40b39e2769a'],
    ['nombre' => 'Funda Transparente', 'embed' => 'https://sketchfab.com/models/07088cf2e74f48a9bcdc38164e608cff/embed', 'enlace' => 'https://sketchfab.com/3d-models/funda-transparente-07088cf2e74f48a9bcdc38164e608cff?utm_medium=embed&utm_campaign=share-popup&utm_content=07088cf2e74f48a9bcdc38164e608cff'],
    ['nombre' => 'Chip Movistar', 'embed' => 'https://sketchfab.com/models/73e8a083471044a988b833f080cc05e2/embed', 'enlace' => 'https://sketchfab.com/3d-models/chip-movistar-73e8a083471044a988b833f080cc05e2?utm_medium=embed&utm_campaign=share-popup&utm_content=73e8a083471044a988b833f080cc05e2'],
    ['nombre' => 'Reloj Azul', 'embed' => 'https://sketchfab.com/models/e818e3da4f344cd19fdbc048b72fad0b/embed', 'enlace' => 'https://sketchfab.com/3d-models/reloj-azul-e818e3da4f344cd19fdbc048b72fad0b?utm_medium=embed&utm_campaign=share-popup&utm_content=e818e3da4f344cd19fdbc048b72fad0b'],
    ['nombre' => 'Reloj Dorado', 'embed' => 'https://sketchfab.com/models/ddc4b28d26cd46038c10fe4b8154f790/embed', 'enlace' => 'https://sketchfab.com/3d-models/relo-j-dorado-ddc4b28d26cd46038c10fe4b8154f790?utm_medium=embed&utm_campaign=share-popup&utm_content=ddc4b28d26cd46038c10fe4b8154f790'],
    ['nombre' => 'Reloj Plateado', 'embed' => 'https://sketchfab.com/models/36841a3910664966b6d0e5977144beed/embed', 'enlace' => 'https://sketchfab.com/3d-models/reloj-plateado-2-36841a3910664966b6d0e5977144beed?utm_medium=embed&utm_campaign=share-popup&utm_content=36841a3910664966b6d0e5977144beed'],
];

$titulo = 'Menú 3D';
$pagina = 'menu3d';
require 'encabezado.php';
?>

        <section class="seccion">
            <div class="contenedor-ancho">
                <div class="seccion-titulo">
                    <h1>Productos en 3D</h1>
                    <p>Gira, acerca y mira cada producto desde todos los ángulos antes de comprarlo.</p>
                </div>

                <div class="modelos-grid">
                    <?php foreach ($modelos as $m): ?>
                    <article class="tarjeta modelo">
                        <h3><?= e($m['nombre']) ?></h3>
                        <iframe title="<?= e($m['nombre']) ?> en 3D" src="<?= e($m['embed']) ?>" loading="lazy"
                                allow="autoplay; fullscreen; xr-spatial-tracking" allowfullscreen></iframe>
                        <p class="modelo-credito">
                            <a href="<?= e($m['enlace']) ?>" target="_blank" rel="nofollow noopener"><?= e($m['nombre']) ?></a>
                            por <a href="https://sketchfab.com/crisorte" target="_blank" rel="nofollow noopener">crisorte</a>
                            en <a href="https://sketchfab.com" target="_blank" rel="nofollow noopener">Sketchfab</a>
                        </p>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

<?php
require 'pie.php';
