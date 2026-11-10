<?php
require 'auth.php';
require 'conexion.php';

$totalProductos = (int) $conn->query('SELECT COUNT(*) FROM productos')->fetch_row()[0];
$pocoStock      = (int) $conn->query('SELECT COUNT(*) FROM productos WHERE cantidad <= 3')->fetch_row()[0];
$noLeidos       = (int) $conn->query('SELECT COUNT(*) FROM contactos WHERE leido = 0')->fetch_row()[0];
$ventasMes      = $conn->query(
    'SELECT COUNT(*) AS num, COALESCE(SUM(total), 0) AS total FROM ventas
     WHERE fecha >= DATE_FORMAT(CURDATE(), "%Y-%m-01")'
)->fetch_assoc();

$titulo = 'Inicio';
$seccion = 'inicio';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Hola, <?= e($_SESSION['usuario']) ?></h1>
                <p>Este es el resumen de tu tienda.</p>
            </div>
        </div>

        <div class="resumen-grid">
            <a href="admin.php" class="tarjeta resumen">
                <i class="fa-solid fa-box" aria-hidden="true"></i>
                <span class="resumen-numero"><?= $totalProductos ?></span>
                <span class="resumen-texto">Productos en la tienda</span>
            </a>
            <a href="ventas.php" class="tarjeta resumen<?= $pocoStock ? ' alerta' : '' ?>">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                <span class="resumen-numero"><?= $pocoStock ?></span>
                <span class="resumen-texto">Con 3 piezas o menos</span>
            </a>
            <a href="mensajes.php?filtro=no_leidos" class="tarjeta resumen<?= $noLeidos ? ' peligro' : '' ?>">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <span class="resumen-numero"><?= $noLeidos ?></span>
                <span class="resumen-texto">Mensajes sin leer</span>
            </a>
            <a href="ventas.php" class="tarjeta resumen">
                <i class="fa-solid fa-sack-dollar" aria-hidden="true"></i>
                <span class="resumen-numero">$<?= number_format((float) $ventasMes['total'], 0) ?></span>
                <span class="resumen-texto"><?= (int) $ventasMes['num'] ?> venta(s) este mes</span>
            </a>
        </div>

        <section class="tarjeta admin-seccion">
            <h2>Accesos rápidos</h2>
            <div class="accesos-grid">
                <a href="admin.php" class="btn btn-suave"><i class="fa-solid fa-plus" aria-hidden="true"></i> Agregar producto</a>
                <a href="admin_promociones.php" class="btn btn-suave"><i class="fa-solid fa-tags" aria-hidden="true"></i> Administrar promociones</a>
                <a href="nuevo usuario.php" class="btn btn-suave"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Dar de alta usuario</a>
                <button type="button" id="botonApi" class="btn btn-suave"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Enviar reporte de inventario</button>
            </div>
        </section>
<?php
$scripts_pie = '<script src="js/menu.js"></script>';
require 'admin_pie.php';
