<?php
require_once("./bbdd/conecta.php");
$conexion = getConexion();

$id = intval($_GET['id']);

if ($id > 0) {
    // Obtener las rutas de los archivos asociados al producto
    $query = "SELECT Foto, FotoFondo FROM Productos WHERE ID = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $foto = $producto['Foto'];
        $fotoFondo = $producto['FotoFondo'];

        // Eliminar archivos locales si existen
        if ($foto && file_exists($foto)) {
            unlink($foto);
        }
        if ($fotoFondo && file_exists($fotoFondo)) {
            unlink($fotoFondo);
        }
    }

    // Eliminar registros relacionados en las tablas ProductoCoaches y ProductoAtributos
    $conexion->query("DELETE FROM ProductoCoaches WHERE ID_Producto = $id");
    $conexion->query("DELETE FROM ProductoAtributos WHERE ID_Producto = $id");

    // Eliminar el producto
    $sql = "DELETE FROM Productos WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Servicio eliminado correctamente.";
    } else {
        echo "Error al eliminar el servicio.";
    }
} else {
    echo "ID de servicio no válido.";
}

$conexion->close();
?>

