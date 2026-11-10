<?php
// Encabezado común de la tienda (menú, buscador y carrito).
// Antes de incluirlo, cada página define:
//   $titulo     -> texto de la pestaña del navegador
//   $pagina     -> 'inicio', 'servicios', 'productos', 'menu3d' o 'contacto' (marca el menú)
//   $css_extra  -> (opcional) lista de hojas de estilo extra, ej. la de Swiper
// Requiere conexion.php (usa la función e()).
$titulo    = $titulo ?? 'Tienda';
$pagina    = $pagina ?? '';
$css_extra = $css_extra ?? [];

$menu = [
    'inicio'    => ['inicio rulfoos.php', 'Inicio'],
    'productos' => ['productos rulfoos php.php', 'Productos'],
    'servicios' => ['servicios rulfoos php.php', 'Servicios'],
    'menu3d'    => ['menu3d.php', 'Menú 3D'],
    'contacto'  => ['contacto rulfos php.php', 'Contacto'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | RULFOOS Accesorios</title>
    <meta name="description" content="RULFOOS: accesorios para celular, audífonos, relojes y reparación de teléfonos en San Francisco Tlalcilalcalpan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php foreach ($css_extra as $hoja): ?>
    <link rel="stylesheet" href="<?= e($hoja) ?>">
    <?php endforeach; ?>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/tienda.css">
</head>
<body class="pagina-<?= e($pagina) ?>">
    <a class="saltar" href="#contenido">Saltar al contenido</a>

    <header class="cabecera">
        <div class="contenedor-ancho cabecera-fila">
            <a href="inicio rulfoos.php" class="logo">RULFOOS<span>Accesorios</span></a>

            <input type="checkbox" id="menu-toggle" class="menu-toggle">
            <nav class="navegacion" aria-label="Principal">
                <ul>
                    <?php foreach ($menu as $clave => [$enlace, $texto]): ?>
                    <li>
                        <a href="<?= e($enlace) ?>"<?= $clave === $pagina ? ' class="activo" aria-current="page"' : '' ?>><?= e($texto) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="cabecera-acciones">
                <button type="button" class="icono-boton" id="suggestion-box" aria-label="Buscar productos" aria-expanded="false" aria-controls="suggestion-panel">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </button>

                <div class="carrito-menu" id="carrito-menu">
                    <button type="button" class="icono-boton" id="img-carrito" aria-label="Ver carrito" aria-expanded="false" aria-controls="carrito">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.5L22 8H6"/></svg>
                        <span class="carrito-contador" id="carrito-contador" hidden>0</span>
                    </button>
                    <div id="carrito" class="carrito-desplegable">
                        <p class="carrito-desplegable-titulo">Tu carrito</p>
                        <table id="lista-carrito">
                            <tbody></tbody>
                        </table>
                        <p class="total-mini">Total <span id="total-mini">$0.00</span></p>
                        <div class="carrito-desplegable-acciones">
                            <a href="#" id="vaciar-carrito" class="btn btn-secundario btn-chico">Vaciar</a>
                            <a href="productos rulfoos php.php#carrito-pago" class="btn btn-primario btn-chico">Ir a pagar</a>
                        </div>
                    </div>
                </div>

                <label for="menu-toggle" class="boton-menu" aria-label="Abrir menú">
                    <span></span><span></span><span></span>
                </label>
            </div>
        </div>

        <!-- Buscador (se abre con la lupa) -->
        <div id="suggestion-panel">
            <div class="contenedor-ancho">
                <label for="product-search" class="saltar">Buscar productos</label>
                <input type="search" id="product-search" placeholder="Buscar audífonos, fundas, relojes..." autocomplete="off">
                <ul id="suggestions-list"></ul>
            </div>
        </div>
    </header>

    <main id="contenido">
