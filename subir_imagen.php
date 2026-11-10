<?php
// Guarda una imagen subida desde un formulario y regresa su ruta (ej. "imgg/a1b2c3d4e5f60708.png").
// Regresa null si no se eligió archivo. Lanza RuntimeException si algo sale mal.
// Solo acepta imágenes reales (JPG, PNG, GIF, WEBP) y les pone un nombre nuevo,
// así nadie puede subir un archivo .php y ejecutarlo en el servidor.
function guardarImagen($campo, $carpeta)
{
    if (empty($_FILES[$campo]['name'])) {
        return null;
    }
    if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Error al subir la imagen (código ' . $_FILES[$campo]['error'] . ').');
    }

    $tipos = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    $info = @getimagesize($_FILES[$campo]['tmp_name']);
    if ($info === false || !isset($tipos[$info[2]])) {
        throw new RuntimeException('El archivo no es una imagen válida (usa JPG, PNG, GIF o WEBP).');
    }

    $carpeta = rtrim($carpeta, '/');
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    $destino = $carpeta . '/' . bin2hex(random_bytes(8)) . '.' . $tipos[$info[2]];
    if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
        throw new RuntimeException('No se pudo guardar la imagen en el servidor.');
    }
    return $destino;
}

// Borra una imagen de la carpeta imgg/ solo si ningún producto ni promoción la sigue usando.
function borrarImagenSiNoSeUsa($conn, $ruta)
{
    if (!$ruta || strpos($ruta, 'imgg/') !== 0 || strpos($ruta, '..') !== false) {
        return;
    }
    $stmt = $conn->prepare(
        'SELECT (SELECT COUNT(*) FROM productos WHERE imagen = ? OR imagen_qr = ?)
              + (SELECT COUNT(*) FROM promociones WHERE imag = ?) AS usos'
    );
    $stmt->bind_param('sss', $ruta, $ruta, $ruta);
    $stmt->execute();
    $usos = (int) $stmt->get_result()->fetch_assoc()['usos'];
    if ($usos === 0 && is_file($ruta)) {
        unlink($ruta);
    }
}
