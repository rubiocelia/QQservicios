<?php
require_once("./bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener ID de la galería
$idGaleria = $_POST['id_galeria'];
$contenidos = $_POST['contenidos'];

// Añadir contenido a la galería
foreach ($contenidos as $idContenido) {
    $query = "INSERT INTO GaleriaContenido (ID_Galeria, ID_Contenido) VALUES (?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $idGaleria, $idContenido);
    $stmt->execute();
}

header("Location: ver_galeria.php?id=" . $idGaleria);
exit();
?>
