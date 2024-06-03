<?php
include './bbdd/conecta.php';
$conexion = getConexion();

$umbral_inactividad = 10; // Umbral de inactividad en minutos
$fecha_limite = date('Y-m-d H:i:s', strtotime("-{$umbral_inactividad} minutes"));

$query_finalizar_sesiones = "UPDATE Sesiones SET FechaFin = ? WHERE UltimoLatido < ? AND FechaFin IS NULL";
$stmt_finalizar = $conexion->prepare($query_finalizar_sesiones);
$fecha_fin = date('Y-m-d H:i:s');
$stmt_finalizar->bind_param('ss', $fecha_fin, $fecha_limite);
$stmt_finalizar->execute();
?>
