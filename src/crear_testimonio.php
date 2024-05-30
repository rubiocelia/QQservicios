<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$nombre = $_POST['nombre'];
$subtitulo = $_POST['subtitulo'];
$descripcion = $_POST['descripcion'];
$id_producto = intval($_POST['producto']);

$foto = subirArchivo('foto', './archivos/testimonios/', '');

$insertQuery = "INSERT INTO Testimonios (Nombre, Subtitulo, Descripcion, Foto, ID_Producto) VALUES (?, ?, ?, ?, ?)";
$insertStmt = $conexion->prepare($insertQuery);
$insertStmt->bind_param("ssssi", $nombre, $subtitulo, $descripcion, $foto, $id_producto);

$response = [];
if ($insertStmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $insertStmt->error;
}

echo json_encode($response);
?>
