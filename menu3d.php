<?php
// Conectar con la base de datos
$conn = new mysqli("localhost", "root", "", "gool");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los productos con enlaces de Sketchfab
$sql = "SELECT nombre, url_sketchfab FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Productos 3D</title>
    <style>
    /* Estilos generales */
    body {
        font-family: 'Arial', sans-serif;
        background-color:rgb(190, 190, 190);
        margin: 0;
        padding: 0;
        text-align: center;
    }

    h1 {
        margin: 40px 0;
        color: white;
        font-size: 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    /* Contenedor de los productos */
    .productos {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 20px 20px;
        max-width: 1200px;
        margin: auto;
        height: auto;
        justify-items: center;
    }

    /* Tarjeta de producto */
    .producto {
        background: linear-gradient(135deg, #ff6f61, #ff4e39);
        color: white;
        border-radius: 25px;
        padding: 25px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
        overflow: hidden;
        text-align: center;
        position: relative;
    }

    .producto:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        filter: brightness(1.1);
    }

    .producto h2 {
        font-size: 24px;
        margin-bottom: 15px;
        font-weight: 600;
        text-transform: uppercase;
        text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.2);
    }

    /* Iframe de Sketchfab */
    .sketchfab-embed-wrapper {
        width: 100%;
        height: auto;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    /* Añadir una sombra sutil al pasar el cursor sobre el iframe */
    .sketchfab-embed-wrapper:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Estilo del enlace */
    .producto p a {
        font-weight: bold;
        color: #ffdf00;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .producto p a:hover {
        color: #ffeb3b;
    }

    /* Estilo para el texto del producto */
    .producto p {
        font-size: 14px;
        color:rgb(42, 66, 188);
        margin-top: 10px;
    }

    .btn_back{
        padding: 10px;
        background-color: orange;
        border-radius: 15px;
        text-decoration: none;
        color: black;
        font-family: 'Arial', sans-serif;
    }
</style>

</head>
<body>

    <h1>Menú de Productos en 3D</h1>
    <a href="inicio rulfoos.php" class="btn_back">Regresar</a>

    <div class="productos">
        <!-- Producto Huawei Freebuds Pro 3 -->
<div class="producto">
    <h2>Huawei Freebuds Pro 3</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Huawei Freebuds Pro 3" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/3a70da1798e44bb9a383dcf666bb69b7/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/hauwei-freebuds-pro-3-3a70da1798e44bb9a383dcf666bb69b7?utm_medium=embed&utm_campaign=share-popup&utm_content=3a70da1798e44bb9a383dcf666bb69b7" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Huawei Freebuds Pro 3</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=3a70da1798e44bb9a383dcf666bb69b7" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=3a70da1798e44bb9a383dcf666bb69b7" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Airpods Pro 2 -->
<div class="producto">
    <h2>Airpods Pro 2</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Airpods Pro 2" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/008a88e800cf4bff8ad0d20e99f5c81f/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/airpods-pro-2-008a88e800cf4bff8ad0d20e99f5c81f?utm_medium=embed&utm_campaign=share-popup&utm_content=008a88e800cf4bff8ad0d20e99f5c81f" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Airpods Pro 2</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=008a88e800cf4bff8ad0d20e99f5c81f" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=008a88e800cf4bff8ad0d20e99f5c81f" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Samsung Buds Pro 3 -->
<div class="producto">
    <h2>Samsung Buds Pro 3</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Samsung Buds Pro 3" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/aba9c22695414d75a74a3589d9885653/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/samsung-buds-pro-3-aba9c22695414d75a74a3589d9885653?utm_medium=embed&utm_campaign=share-popup&utm_content=aba9c22695414d75a74a3589d9885653" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Samsung Buds Pro 3</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=aba9c22695414d75a74a3589d9885653" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=aba9c22695414d75a74a3589d9885653" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Fundas Pareja -->
<div class="producto">
    <h2>Fundas Pareja</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Fundas Pareja" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/3ac68cd279194bd583aea40b39e2769a/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/fundas-pareja-3ac68cd279194bd583aea40b39e2769a?utm_medium=embed&utm_campaign=share-popup&utm_content=3ac68cd279194bd583aea40b39e2769a" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Fundas Pareja</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=3ac68cd279194bd583aea40b39e2769a" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=3ac68cd279194bd583aea40b39e2769a" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Funda Transparente -->
<div class="producto">
    <h2>Funda Transparente</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Funda Transparente" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/07088cf2e74f48a9bcdc38164e608cff/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/funda-transparente-07088cf2e74f48a9bcdc38164e608cff?utm_medium=embed&utm_campaign=share-popup&utm_content=07088cf2e74f48a9bcdc38164e608cff" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Funda Transparente</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=07088cf2e74f48a9bcdc38164e608cff" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=07088cf2e74f48a9bcdc38164e608cff" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Chip Movistar -->
<div class="producto">
    <h2>Chip Movistar</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Chip Movistar" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/73e8a083471044a988b833f080cc05e2/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/chip-movistar-73e8a083471044a988b833f080cc05e2?utm_medium=embed&utm_campaign=share-popup&utm_content=73e8a083471044a988b833f080cc05e2" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Chip Movistar</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=73e8a083471044a988b833f080cc05e2" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=73e8a083471044a988b833f080cc05e2" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Reloj Azul -->
<div class="producto">
    <h2>Reloj Azul</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Reloj Azul" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/e818e3da4f344cd19fdbc048b72fad0b/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/reloj-azul-e818e3da4f344cd19fdbc048b72fad0b?utm_medium=embed&utm_campaign=share-popup&utm_content=e818e3da4f344cd19fdbc048b72fad0b" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Reloj Azul</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=e818e3da4f344cd19fdbc048b72fad0b" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=e818e3da4f344cd19fdbc048b72fad0b" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Reloj Dorado -->
<div class="producto">
    <h2>Reloj Dorado</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Reloj Dorado" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/ddc4b28d26cd46038c10fe4b8154f790/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/relo-j-dorado-ddc4b28d26cd46038c10fe4b8154f790?utm_medium=embed&utm_campaign=share-popup&utm_content=ddc4b28d26cd46038c10fe4b8154f790" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Reloj Dorado</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=ddc4b28d26cd46038c10fe4b8154f790" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=ddc4b28d26cd46038c10fe4b8154f790" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>

<!-- Producto Reloj Plateado -->
<div class="producto">
    <h2>Reloj Plateado</h2>
    <div class="sketchfab-embed-wrapper">
        <iframe title="Reloj Plateado" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" allow="autoplay; fullscreen; xr-spatial-tracking" xr-spatial-tracking execution-while-out-of-viewport execution-while-not-rendered web-share src="https://sketchfab.com/models/36841a3910664966b6d0e5977144beed/embed"></iframe>
        <p style="font-size: 13px; font-weight: normal; margin: 5px; color: #4A4A4A;">
            <a href="https://sketchfab.com/3d-models/reloj-plateado-2-36841a3910664966b6d0e5977144beed?utm_medium=embed&utm_campaign=share-popup&utm_content=36841a3910664966b6d0e5977144beed" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Reloj Plateado</a> by <a href="https://sketchfab.com/crisorte?utm_medium=embed&utm_campaign=share-popup&utm_content=36841a3910664966b6d0e5977144beed" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">crisorte</a> on <a href="https://sketchfab.com?utm_medium=embed&utm_campaign=share-popup&utm_content=36841a3910664966b6d0e5977144beed" target="_blank" rel="nofollow" style="font-weight: bold; color: #1CAAD9;">Sketchfab</a>
        </p>
    </div>
</div>



        



    </div>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
