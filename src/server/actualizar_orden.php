<?php
require_once("../bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

foreach ($data['orden'] as $item) {
    $idContenido = $item['id'];
    $nuevoOrden = $item['orden'];

    $query = "UPDATE GaleriaContenido SET Orden = ? WHERE ID_Contenido = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $nuevoOrden, $idContenido);
    $stmt->execute();
}

echo json_encode(['success' => true]);
?>
