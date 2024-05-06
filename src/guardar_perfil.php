<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo_electronico'])) {
    header("Location: formulario_inicio_sesion.php");
    exit();
}

require_once("./bbdd/conecta.php");
$conexion = getConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación y saneamiento
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellidos = $conexion->real_escape_string($_POST['apellidos']);
    $email = $conexion->real_escape_string($_POST['email']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $organizacion = $conexion->real_escape_string($_POST['organizacion']);
    $correoElectronico = $_SESSION['correo_electronico'];

    // SQL para actualizar los datos del usuario
    $sql = "UPDATE Usuarios SET Nombre=?, Apellidos=?, Correo_electronico=?, Numero_telefono=?, Organizacion=? WHERE Correo_electronico=?";
    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error de preparación SQL: ' . $conexion->error]);
        exit;
    }

    $stmt->bind_param("ssssss", $nombre, $apellidos, $email, $telefono, $organizacion, $correoElectronico);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar datos: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conexion->close();
    exit;
}

?>