<?php
require_once("../bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener ID del contenido
$idContenido = $_GET['id'];

// Eliminar contenido de la tabla GaleriaContenido
$query = "DELETE FROM GaleriaContenido WHERE ID_Contenido = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idContenido);
$stmt->execute();

header("Location: " . $_SERVER['HTTP_REFERER']);
?>
