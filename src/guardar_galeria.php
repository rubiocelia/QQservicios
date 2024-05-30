<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreGaleria'])) {
    $nombreGaleria = $_POST['nombreGaleria'];

    $insertQuery = "INSERT INTO Galerias (Nombre_galeria) VALUES (?)";
    $insertStmt = $conexion->prepare($insertQuery);
    $insertStmt->bind_param("s", $nombreGaleria);

    if ($insertStmt->execute()) {
        $idGaleria = $conexion->insert_id;
        echo json_encode(['success' => true, 'id' => $idGaleria]);
    } else {
        echo json_encode(['success' => false, 'error' => $insertStmt->error]);
    }

    $insertStmt->close();
    $conexion->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>