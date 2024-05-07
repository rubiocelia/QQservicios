<?php
session_start();

// Comprobamos si hay un archivo y si hay una sesión activa
if (!isset($_SESSION['id_usuario']) || !isset($_FILES['nuevaFoto'])) {
    echo "Error: No se ha podido procesar la solicitud.";
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$archivo = $_FILES['nuevaFoto'];

// Validar que el archivo es una imagen
if ($archivo['type'] !== 'image/jpeg' && $archivo['type'] !== 'image/png') {
    echo "Error: Formato de archivo no admitido. Por favor, sube una imagen JPEG o PNG.";
    exit;
}

// Path donde se guardará la imagen
$directorioDestino = "path/a/tu/directorio/de/imagenes/";
$nombreArchivo = $idUsuario . '_' . time() . '.jpg'; // Crear un nombre único para el archivo
$rutaCompleta = $directorioDestino . $nombreArchivo;

// Intentar subir la imagen
if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
    // Actualizar la ruta de la imagen en la base de datos
    require_once("./bbdd/conecta.php");
    $conexion = getConexion();
    $sql = "UPDATE Usuarios SET Foto = ? WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $rutaCompleta, $idUsuario);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Foto de perfil actualizada correctamente.";
    } else {
        echo "Error al actualizar la foto de perfil.";
    }
    $conexion->close();
} else {
    echo "Error al subir el archivo.";
}
?>