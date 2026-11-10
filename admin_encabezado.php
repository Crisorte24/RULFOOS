<?php
// Encabezado común del panel de administración.
// Antes de incluirlo, cada página define:
//   $titulo  -> texto de la pestaña
//   $seccion -> 'inicio', 'productos', 'promociones', 'mensajes', 'ventas' o 'usuarios'
// Requiere auth.php y conexion.php.
$titulo  = $titulo ?? 'Administración';
$seccion = $seccion ?? '';

$sinLeer = (int) $conn->query('SELECT COUNT(*) FROM contactos WHERE leido = 0')->fetch_row()[0];

$menuAdmin = [
    'inicio'      => ['menu_add.php', 'Inicio'],
    'productos'   => ['admin.php', 'Productos'],
    'promociones' => ['admin_promociones.php', 'Promociones'],
    'mensajes'    => ['mensajes.php', 'Mensajes'],
    'ventas'      => ['ventas.php', 'Ventas'],
    'usuarios'    => ['nuevo usuario.php', 'Usuarios'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title><?= e($titulo) ?> | Admin RULFOOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin">
    <header class="admin-barra">
        <div class="contenedor-ancho cabecera-fila">
            <a href="menu_add.php" class="logo">RULFOOS<span>Administración</span></a>

            <input type="checkbox" id="menu-toggle" class="menu-toggle">
            <nav class="navegacion" aria-label="Administración">
                <ul>
                    <?php foreach ($menuAdmin as $clave => [$enlace, $texto]): ?>
                    <li>
                        <a href="<?= e($enlace) ?>"<?= $clave === $seccion ? ' class="activo" aria-current="page"' : '' ?>>
                            <?= e($texto) ?><?php if ($clave === 'mensajes' && $sinLeer): ?><span class="badge"><?= $sinLeer ?></span><?php endif; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                    <li><a href="inicio rulfoos.php" target="_blank" rel="noopener">Ver tienda <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a></li>
                </ul>
            </nav>

            <div class="cabecera-acciones">
                <div class="admin-usuario">
                    <span class="nombre-usuario"><i class="fa-solid fa-user" aria-hidden="true"></i> <?= e($_SESSION['usuario']) ?></span>
                    <a href="logout.php" class="btn btn-secundario btn-chico">Salir</a>
                </div>
                <label for="menu-toggle" class="boton-menu" aria-label="Abrir menú">
                    <span></span><span></span><span></span>
                </label>
            </div>
        </div>
    </header>

    <main class="contenedor-ancho admin-contenido">
