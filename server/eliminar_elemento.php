<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de elemento no proporcionado']);
    exit;
}

$idElemento = $_GET['id'];

// Obtener la ruta del archivo
$query = "SELECT RutaArchivos FROM carruselMultimedia WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idElemento);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
    exit;
}

$elemento = $result->fetch_assoc();
$rutaArchivo = $elemento['RutaArchivos'];

// Eliminar el archivo del servidor si existe
if ($rutaArchivo && file_exists('../' . $rutaArchivo)) {
    unlink('../' . $rutaArchivo);
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
