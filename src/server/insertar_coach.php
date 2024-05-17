<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $titulacion = $_POST['titulacion'];
    $descripcion = $_POST['descripcion'];
    $linkedin = $_POST['linkedin'];
    $video = $_POST['video'];
    $general = $_POST['general'];
    $foto = $_POST['foto'];

    $query = "INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Descripcion, LinkedIn, Video, General, Foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $nombre, $apellidos, $titulacion, $descripcion, $linkedin, $video, $general, $foto);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al insertar en la base de datos: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>
