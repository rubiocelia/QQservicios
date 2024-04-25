<?php
// Incluir archivo de conexión a la base de datos
include '../bbdd/conecta.php';

// Obtener datos del formulario
$correo_electronico = $_POST['correo_electronico'];
$contrasena = $_POST['contrasena'];

// Cifrar la contraseña
$contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);

// Realizar la verificación de las credenciales
$conexion = getConexion();
$query = "SELECT ID_usuario
FROM DatosAcceso
WHERE Usuario = '$correo_electronico' AND Contrasena = '$contrasena_cifrada';
";
$resultado = $conexion->query($query);

if ($resultado->num_rows > 0) {
    // Usuario encontrado, verificar contraseña
    $row = $resultado->fetch_assoc();
    $contrasena_almacenada = obtenerContrasenaUsuario($conexion, $row['ID']);
    if (password_verify($contrasena, $contrasena_almacenada)) {
        // Contraseña válida, iniciar sesión
        session_start();
        $_SESSION['user_id'] = $row['ID'];
        // Devolver respuesta JSON con éxito
        echo json_encode(array("success" => true, "redirect" => "mi_cuenta.php"));
    } else {
        // Contraseña incorrecta, devolver respuesta JSON con error
        echo json_encode(array("success" => false, "message" => "Error de credenciales"));
    }
} else {
    // Usuario no encontrado, devolver respuesta JSON con error
    echo json_encode(array("success" => false, "message" => "Error de credenciales"));
}

// Función para obtener la contraseña hasheada de un usuario
function obtenerContrasenaUsuario($conexion, $usuario_id)
{
    $query = "SELECT Contrasena FROM DatosAcceso WHERE ID_usuario = $usuario_id";
    $resultado = $conexion->query($query);
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        return $row['Contrasena'];
    } else {
        return false;
    }
}
?>
