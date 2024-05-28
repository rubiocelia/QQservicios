<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$tipo = $_POST['tipo'];
$descripcion = $_POST['descripcion'];
$url_local = isset($_FILES['url_local']) ? $_FILES['url_local'] : null;
$url_youtube = isset($_POST['url_youtube']) ? $_POST['url_youtube'] : null;

if ($tipo == 'foto' || $tipo == 'video_local') {
    if ($url_local && $url_local['tmp_name']) {
        $target_dir = "./archivos/galerias/";
        $target_file = $target_dir . basename($url_local['name']);
        if (move_uploaded_file($url_local["tmp_name"], $target_file)) {
            $sql = "INSERT INTO ContenidoMultimedia (Tipo, URL_Local, Descripcion) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $tipo, $target_file, $descripcion);

            if ($stmt->execute()) {
                echo "Contenido subido correctamente\n";
            } else {
                echo "Error al subir contenido: " . $stmt->error . "\n";
            }
        } else {
            echo "Error al mover el archivo a la carpeta de destino.\n";
        }
    } else {
        echo "No se proporcionó un archivo local para el tipo $tipo.\n";
    }
} elseif ($tipo == 'video_youtube') {
    if ($url_youtube) {
        $sql = "INSERT INTO ContenidoMultimedia (Tipo, URL_Youtube, Descripcion) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $tipo, $url_youtube, $descripcion);

        if ($stmt->execute()) {
            echo "Contenido subido correctamente\n";
        } else {
            echo "Error al subir contenido: " . $stmt->error . "\n";
        }
    } else {
        echo "No se proporcionó una URL de YouTube para el tipo $tipo.\n";
    }
} else {
    echo "Tipo de contenido desconocido: $tipo\n";
}

$conexion->close();
?>
