<?php
session_start();
include('../bbdd/conecta.php');
$conn = getConexion();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_archivo']) && isset($_POST['nuevo_estado'])) {
        $idArchivo = $_POST['id_archivo'];
        $nuevoEstado = $_POST['nuevo_estado'];

        $query = "UPDATE ArchivosUsuarios SET Deshabilitado = ? WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $nuevoEstado, $idArchivo);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Error al actualizar el estado del archivo: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'ID de archivo o nuevo estado no proporcionado';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

$conn->close();
echo json_encode($response);
?>
