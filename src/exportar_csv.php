<?php
include './bbdd/conecta.php';

if (isset($_GET['id'])) {
    $id_usuario = (int)$_GET['id'];
    
    $conexion = getConexion();
    $query = "SELECT * FROM Sesiones WHERE ID_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $filename = "sesiones_usuario_{$id_usuario}.csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'ID_usuario', 'FechaInicio', 'FechaFin', 'UltimoLatido'));
        
        while ($fila = $resultado->fetch_assoc()) {
            fputcsv($output, $fila);
        }
        fclose($output);
    } else {
        echo "No hay datos para exportar.";
    }
    $stmt->close();
    $conexion->close();
} else {
    echo "ID de usuario no proporcionado.";
}
?>
