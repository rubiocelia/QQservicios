<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$nombre = $data['nombre'];

$query = "INSERT INTO carruselMultimedia (Nombre_carrusel) VALUES (?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nombre);

if ($stmt->execute()) {
    $id = $stmt->insert_id;
    echo json_encode(['success' => true, 'id' => $id]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
