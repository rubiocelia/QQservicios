<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['idUsuario'];
    $idProducto = $_POST['producto'];
    $descripcion = $_POST['descripcion'];
    $fecha = date('Y-m-d');

    $uploadDir = '../archivos/archivosClientes/';
    $fileName = basename($_FILES['archivo']['name']);
    $uploadFile = $uploadDir . $fileName;
    $uploadDirSQL = './archivos/archivosClientes/' . $fileName;


    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadFile)) {
        $query = "INSERT INTO ArchivosUsuarios (Ruta, Descripcion, Fecha, Deshabilitado, ID_Producto, ID_usuario) VALUES (?, ?, ?, 0, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssii", $uploadDirSQL, $descripcion, $fecha, $idProducto, $idUsuario);

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

    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>
