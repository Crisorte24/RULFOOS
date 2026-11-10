<?php
// Mensajes que llegan desde el formulario de contacto.
require 'auth.php';
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'leido' || $accion === 'no_leido') {
        $valor = $accion === 'leido' ? 1 : 0;
        $stmt = $conn->prepare('UPDATE contactos SET leido = ? WHERE id = ?');
        $stmt->bind_param('ii', $valor, $id);
        $stmt->execute();
    } elseif ($accion === 'todos_leidos') {
        $conn->query('UPDATE contactos SET leido = 1 WHERE leido = 0');
        flash('Todos los mensajes se marcaron como leídos.');
    } elseif ($accion === 'eliminar') {
        $stmt = $conn->prepare('DELETE FROM contactos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        flash('Mensaje eliminado.');
    }

    $filtro = ($_POST['filtro'] ?? '') === 'no_leidos' ? '?filtro=no_leidos' : '';
    header('Location: mensajes.php' . $filtro);
    exit();
}

$soloNoLeidos = ($_GET['filtro'] ?? '') === 'no_leidos';
$sql = 'SELECT * FROM contactos' . ($soloNoLeidos ? ' WHERE leido = 0' : '') . ' ORDER BY fecha DESC, id DESC';
$mensajes = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
$noLeidos = (int) $conn->query('SELECT COUNT(*) FROM contactos WHERE leido = 0')->fetch_row()[0];
$aviso = flash();
$filtroCampo = $soloNoLeidos ? 'no_leidos' : '';

$titulo = 'Mensajes';
$seccion = 'mensajes';
require 'admin_encabezado.php';
?>
        <div class="admin-titulo">
            <div>
                <h1>Mensajes de contacto</h1>
                <p>Lo que te escriben los clientes desde la página de contacto.</p>
            </div>
        </div>

        <?php if ($aviso): ?>
            <p class="aviso ok"><?php echo e($aviso); ?></p>
        <?php endif; ?>

        <section class="tarjeta admin-seccion">
            <div class="filtros">
                <a href="mensajes.php" class="btn btn-chico <?php echo $soloNoLeidos ? 'btn-secundario' : 'btn-oscuro'; ?>">Todos</a>
                <a href="mensajes.php?filtro=no_leidos" class="btn btn-chico <?php echo $soloNoLeidos ? 'btn-oscuro' : 'btn-secundario'; ?>">
                    Sin leer<?php if ($noLeidos): ?><span class="badge"><?php echo $noLeidos; ?></span><?php endif; ?>
                </a>
                <?php if ($noLeidos): ?>
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="filtro" value="<?php echo $filtroCampo; ?>">
                        <button type="submit" name="accion" value="todos_leidos" class="btn btn-suave btn-chico">Marcar todos como leídos</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (!$mensajes): ?>
                <p class="vacio"><?php echo $soloNoLeidos ? 'No tienes mensajes sin leer.' : 'Todavía no han llegado mensajes.'; ?></p>
            <?php else: ?>
            <div class="tabla-scroll">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>De</th>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mensajes as $m): ?>
                        <tr class="<?php echo $m['leido'] ? '' : 'no-leido'; ?>">
                            <td class="sin-salto"><?php echo e(date('d/m/Y H:i', strtotime($m['fecha']))); ?></td>
                            <td>
                                <span class="remitente"><?php echo e($m['nombre']); ?></span><br>
                                <a href="mailto:<?php echo e($m['email']); ?>?subject=<?php echo rawurlencode('Re: ' . ($m['asunto'] !== '' ? $m['asunto'] : 'Tu mensaje a RULFOOS')); ?>"><?php echo e($m['email']); ?></a>
                            </td>
                            <td><?php echo $m['asunto'] !== '' ? e($m['asunto']) : '<span class="ayuda">(sin asunto)</span>'; ?></td>
                            <td class="texto-mensaje"><?php echo nl2br(e($m['mensaje'])); ?></td>
                            <td>
                                <form method="POST" class="tabla-acciones">
                                    <input type="hidden" name="id" value="<?php echo (int) $m['id']; ?>">
                                    <input type="hidden" name="filtro" value="<?php echo $filtroCampo; ?>">
                                    <?php if ($m['leido']): ?>
                                        <button type="submit" name="accion" value="no_leido" class="btn btn-secundario btn-chico">Marcar no leído</button>
                                    <?php else: ?>
                                        <button type="submit" name="accion" value="leido" class="btn btn-suave btn-chico">Marcar leído</button>
                                    <?php endif; ?>
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-peligro btn-chico"
                                            onclick="return confirm('¿Eliminar este mensaje?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>
<?php
require 'admin_pie.php';
