<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'new');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Productos</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 100px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #4CAF50;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
    
</head>
<body>
    <header>
        <h1>Tienda de Productos</h1>
    </header>

    <main>
        <h2>Productos Disponibles</h2>
        <table>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>
            <?php while ($producto = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="imgg/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>"></td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['precio']); ?> €</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Tienda de Productos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
