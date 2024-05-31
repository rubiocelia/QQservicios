<?php
include './bbdd/conecta.php';

function obtenerDatosSemana($id_usuario) {
    $conexion = getConexion();
    $query = "SELECT DATE(FechaInicio) as fecha, COUNT(*) as veces, SUM(TIMESTAMPDIFF(SECOND, FechaInicio, FechaFin)/60) as tiempo
              FROM Sesiones
              WHERE ID_usuario = ? AND FechaInicio >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
              GROUP BY DATE(FechaInicio)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $datos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    return $datos;
}

function obtenerDatosTresMeses($id_usuario) {
    $conexion = getConexion();
    $query = "SELECT DATE_FORMAT(FechaInicio, '%Y-%m') as mes, COUNT(*) as veces
              FROM Sesiones
              WHERE ID_usuario = ? AND FechaInicio >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
              GROUP BY DATE_FORMAT(FechaInicio, '%Y-%m')";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $datos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
    return $datos;
}

$id_usuario = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_usuario > 0) {
    $datos_semana = obtenerDatosSemana($id_usuario);
    $datos_tres_meses = obtenerDatosTresMeses($id_usuario);

    echo json_encode(['semana' => $datos_semana, 'tres_meses' => $datos_tres_meses]);
} else {
    echo json_encode(['error' => 'ID de usuario no válido']);
}
?>
