<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

// Desactivar la visualización de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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
    
    // Manejo de la subida de la foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../archivos/coaches/';
        
        // Generar un nombre único para el archivo
        $fileName = uniqid('coach_', true) . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $uploadFile = $uploadDir . $fileName;
        $RutaFile = './archivos/coaches/' . $fileName;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            // Inserción en la base de datos
            $query = "INSERT INTO Coaches (Nombre, Apellidos, Titulacion, Descripcion, LinkedIn, Video, General, Foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssss", $nombre, $apellidos, $titulacion, $descripcion, $linkedin, $video, $general, $RutaFile);

            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'Error al insertar en la base de datos: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Error al subir el archivo.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Error en la subida del archivo: ' . $_FILES['foto']['error'];
    }

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>
