<?php
// chatbot.php
header('Content-Type: application/json');

// Conexión a la base de datos
require 'conexion.php';

// Obtener el mensaje del usuario
$mensaje = trim($_POST['mensaje'] ?? '');

// Lógica para procesar el mensaje y generar una respuesta
$respuesta = [];

// Función para obtener productos
function obtenerProductos($conn) {
    $result = $conn->query("SELECT nombre FROM productos LIMIT 5");
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row['nombre'];
    }
    return $productos;
}

// Función para obtener promociones
function obtenerPromociones($conn) {
    $result = $conn->query("SELECT nombre_promocion FROM promociones LIMIT 5");
    $promociones = [];
    while ($row = $result->fetch_assoc()) {
        $promociones[] = $row['nombre_promocion'];
    }
    return $promociones;
}

// Respuestas basadas en el mensaje
if (stripos($mensaje, 'productos') !== false || stripos($mensaje, 'quiero ver productos') !== false) {
    $productos = obtenerProductos($conn);
    $respuesta = [
        'respuesta' => 'Aquí tienes algunos productos: ' . implode(', ', $productos)
    ];
} elseif (stripos($mensaje, 'promociones') !== false || stripos($mensaje, 'ofertas') !== false) {
    $promociones = obtenerPromociones($conn);
    $respuesta = [
        'respuesta' => 'Estas son algunas promociones: ' . implode(', ', $promociones)
    ];
} elseif (stripos($mensaje, 'horario') !== false) {
    $respuesta = [
        'respuesta' => 'Nuestro horario de atención es de lunes a viernes de 9:00 a 18:00 y sábados de 10:00 a 14:00.'
    ];
} elseif (stripos($mensaje, 'contacto') !== false || stripos($mensaje, 'número') !== false) {
    $respuesta = [
        'respuesta' => 'Puedes contactarnos al 7294697480 o enviar un correo a contacto@rufoos.com.'
    ];
} elseif (stripos($mensaje, 'ayuda') !== false || stripos($mensaje, 'soporte') !== false) {
    $respuesta = [
        'respuesta' => 'Si necesitas ayuda, por favor indícanos tu consulta y estaremos encantados de ayudarte.'
    ];
} else {
    $respuesta = [
        'respuesta' => 'Lo siento, no entiendo tu mensaje. ¿Puedes especificar más?'
    ];
}

// Cerrar la conexión
$conn->close();

// Devolver la respuesta como JSON
echo json_encode($respuesta);
