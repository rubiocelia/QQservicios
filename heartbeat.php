<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    include './bbdd/conecta.php';
    $conexion = getConexion();

    $id_usuario = $_SESSION['id_usuario'];
    $fecha_actual = date('Y-m-d H:i:s');

    // Actualizar el último latido del usuario en la base de datos
    $query_heartbeat = "UPDATE Sesiones SET UltimoLatido = ? WHERE ID_usuario = ? AND FechaFin IS NULL";
    $stmt_heartbeat = $conexion->prepare($query_heartbeat);
    $stmt_heartbeat->bind_param('si', $fecha_actual, $id_usuario);
    $stmt_heartbeat->execute();

    // Verificar si la actualización fue exitosa
    if ($stmt_heartbeat->affected_rows > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "No se pudo actualizar el latido"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Sesión no válida"));
}
?>
