<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

$idProducto = $_POST['idProducto'];
$link_video = $_POST['link_video'];
$uploadDir = '../archivos/galerias/' . $idProducto . '/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['archivo']['name'])) {
    $fileName = uniqid() . '_' . basename($_FILES['archivo']['name']);
    $uploadFile = $uploadDir . $fileName;
    $RutaFile = './archivos/galerias/' . $idProducto . '/' . $fileName;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) {
        $query = "INSERT INTO carruselMultimedia (Nombre_carrusel, RutaArchivos, Link_Video, ID_Producto) VALUES (?, ?, NULL, ?)";
        $stmt = $conn->prepare($query);
        $nombreCarrusel = "Carrusel " . $idProducto; // Usar un nombre genérico para el carrusel
        $stmt->bind_param("ssi", $nombreCarrusel, $RutaFile, $idProducto);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
        exit;
    }
} elseif (!empty($link_video)) {
    $query = "INSERT INTO carruselMultimedia (Nombre_carrusel, RutaArchivos, Link_Video, ID_Producto) VALUES (?, NULL, ?, ?)";
    $stmt = $conn->prepare($query);
    $nombreCarrusel = "Carrusel " . $idProducto; // Usar un nombre genérico para el carrusel
    $stmt->bind_param("ssi", $nombreCarrusel, $link_video, $idProducto);
} else {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó archivo ni video.']);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
