<?php
require_once("./bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener tipo de contenido y descripción
$tipo = $_POST['tipo'];
$descripcion = $_POST['descripcion'];

if ($tipo === 'foto' && isset($_FILES['foto'])) {
    // Manejar subida de foto
    $targetDir = "./archivos/productos/carruselProducto1/";
    $targetFile = $targetDir . basename($_FILES["foto"]["name"]);
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
        $query = "INSERT INTO ContenidoMultimedia (Tipo, URL_Local, Descripcion) VALUES ('foto', ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $targetFile, $descripcion);
        $stmt->execute();
    }
} elseif ($tipo === 'video_local' && isset($_FILES['video_local'])) {
    // Manejar subida de video local
    $targetDir = "./archivos/productos/carruselProducto1/";
    $targetFile = $targetDir . basename($_FILES["video_local"]["name"]);
    if (move_uploaded_file($_FILES["video_local"]["tmp_name"], $targetFile)) {
        $query = "INSERT INTO ContenidoMultimedia (Tipo, URL_Local, Descripcion) VALUES ('video_local', ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $targetFile, $descripcion);
        $stmt->execute();
    }
} elseif ($tipo === 'video_youtube' && isset($_POST['url_youtube'])) {
    // Manejar URL de YouTube
    $urlYoutube = $_POST['url_youtube'];
    $query = "INSERT INTO ContenidoMultimedia (Tipo, URL_Youtube, Descripcion) VALUES ('video_youtube', ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ss", $urlYoutube, $descripcion);
    $stmt->execute();
}

header("Location: mi_cuenta_admin.php");
exit();
?>