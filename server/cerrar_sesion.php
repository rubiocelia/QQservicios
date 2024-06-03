<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    include '../bbdd/conecta.php';
    $conexion = getConexion();

    $id_usuario = $_SESSION['id_usuario'];
    $fecha_fin = date('Y-m-d H:i:s');

    // Actualizar la fecha de fin de la sesión en la base de datos
    $query_logout = "UPDATE Sesiones SET FechaFin = ? WHERE ID_usuario = ? AND FechaFin IS NULL";
    $stmt_logout = $conexion->prepare($query_logout);
    $stmt_logout->bind_param('si', $fecha_fin, $id_usuario);
    $stmt_logout->execute();

    // Destruir la sesión
    session_destroy();
}
header("Location: ../index.php");
?>
