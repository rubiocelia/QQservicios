<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de elemento no proporcionado.']);
    exit();
}

$idElemento = $_GET['id'];

// Obtener la ruta del archivo para eliminarlo físicamente del servidor
$query = "SELECT RutaArchivos FROM carruselMultimedia WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idElemento);
$stmt->execute();
$result = $stmt->get_result();
$elemento = $result->fetch_assoc();

if ($elemento && !empty($elemento['RutaArchivos']) && file_exists($elemento['RutaArchivos'])) {
    unlink($elemento['RutaArchivos']);  // Eliminar el archivo físico
}

// Eliminar el registro de la base de datos
$query = "DELETE FROM carruselMultimedia WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idElemento);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
