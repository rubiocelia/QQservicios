<?php
require_once("../bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener nombre de la galería
$nombreGaleria = $_GET['nombre'];

// Insertar nueva galería
$query = "INSERT INTO Galerias (Nombre_galeria) VALUES (?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $nombreGaleria);
$stmt->execute();

header("Location: ../mi_cuenta_admin.php");
?>
