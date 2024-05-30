<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$idProducto = intval($_GET['id']);
$testimoniosQuery = "SELECT * FROM Testimonios WHERE ID_Producto = ?";
$testimoniosStmt = $conexion->prepare($testimoniosQuery);
$testimoniosStmt->bind_param("i", $idProducto);
$testimoniosStmt->execute();
$testimoniosResult = $testimoniosStmt->get_result();

$testimonios = [];
while ($testimonio = $testimoniosResult->fetch_assoc()) {
    $testimonios[] = $testimonio;
}

echo json_encode(['testimonios' => $testimonios]);
?>
