<?php
$conn = new mysqli("localhost", "root", "", "gool");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['q']) ? $_GET['q'] : '';

if ($query) {
    $stmt = $conn->prepare("SELECT nombre FROM productos WHERE nombre LIKE ?");
    $searchQuery = "%" . $query . "%";
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode($productos);
}

$conn->close();
?>
