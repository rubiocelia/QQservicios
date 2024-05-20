<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

$nombreCarrusel = $_POST['nombre_carrusel'];
$link_video = $_POST['link_video'];
$uploadDir = '../archivos/galerias/' . $nombreCarrusel . '/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['archivo']['name'])) {
    $fileName = uniqid() . '_' . basename($_FILES['archivo']['name']);
    $uploadFile = $uploadDir . $fileName;
    $RutaFile = './archivos/galerias/' . $nombreCarrusel . '/' . $fileName;

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) {
        $query = "INSERT INTO carruselMultimedia (Nombre_carrusel, RutaArchivos, Link_Video) VALUES (?, ?, NULL)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nombreCarrusel, $RutaFile);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
        exit;
    }
} elseif (!empty($link_video)) {
    $query = "INSERT INTO carruselMultimedia (Nombre_carrusel, RutaArchivos, Link_Video) VALUES (?, NULL, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nombreCarrusel, $link_video);
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
