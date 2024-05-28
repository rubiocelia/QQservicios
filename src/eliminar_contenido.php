<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$id = $_GET['id'];

// Iniciar una transacción para asegurarse de que ambas operaciones (eliminación de contenido y eliminación de referencias) se completan
$conexion->begin_transaction();

try {
    // Eliminar las referencias en galeriacontenido
    $sql = "DELETE FROM GaleriaContenido WHERE ID_Contenido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Obtener el contenido multimedia
    $sql = "SELECT * FROM ContenidoMultimedia WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contenido = $result->fetch_assoc();

    if ($contenido) {
        $archivo_eliminado = true;

        // Eliminar archivo local si existe y es un archivo
        if ($contenido['URL_Local']) {
            $ruta_archivo = $contenido['URL_Local'];
            if (is_file($ruta_archivo) && file_exists($ruta_archivo)) {
                if (!unlink($ruta_archivo)) {
                    $archivo_eliminado = false;
                    echo "Error al eliminar el archivo local.";
                }
            } elseif (is_dir($ruta_archivo)) {
                $archivo_eliminado = false;
                echo "La ruta proporcionada es un directorio, no un archivo.";
            }
        }

        // Si el archivo local se elimina correctamente (o no existe), elimina el registro de la base de datos
        if ($archivo_eliminado) {
            $sql = "DELETE FROM ContenidoMultimedia WHERE ID = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "Contenido eliminado correctamente";
            } else {
                echo "Error al eliminar contenido";
            }
        }
    } else {
        echo "Contenido no encontrado";
    }

    // Confirmar la transacción
    $conexion->commit();
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conexion->rollback();
    echo "Error al eliminar contenido: " . $e->getMessage();
}

$conexion->close();
