<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json');

$nombre = $_POST['nombre'];
$subtitulo = $_POST['subtitulo'];
$descripcion = $_POST['descripcion'];
$idProducto = $_POST['producto'];
$uploadDir = '../archivos/testimonios/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['foto']['name'])) {
    $fileName = uniqid() . '_' . basename($_FILES['foto']['name']);
    $uploadFile = $uploadDir . $fileName;
    $RutaFile = './archivos/testimonios/' . $fileName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
        $query = "INSERT INTO Testimonios (Nombre, Subtitulo, Descripcion, Foto, ID_Producto) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $nombre, $subtitulo, $descripcion, $RutaFile, $idProducto);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir el archivo.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la subida del archivo.']);
}

$conn->close();
?>
