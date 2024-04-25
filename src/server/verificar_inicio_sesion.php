<?php
// Incluir archivo de conexión a la base de datos
include '../bbdd/conecta.php';

// Obtener datos del formulario
$correo_electronico = $_POST['correo_electronico'];
$contrasena = $_POST['contrasena'];

// Realizar la verificación de las credenciales
$conexion = getConexion();
$query = "SELECT Correo_electronico, Contrasena
          FROM DatosAcceso
          WHERE Correo_electronico = '$correo_electronico'";
$resultado = $conexion->query($query);

if ($resultado->num_rows > 0) {
    // Usuario encontrado, verificar contraseña
    $row = $resultado->fetch_assoc();
    $contrasena_almacenada = $row['Contrasena'];
    // Comparar la contraseña proporcionada con la almacenada
    if (password_verify($contrasena, $contrasena_almacenada)) {
        // Contraseña válida, iniciar sesión
        session_start();
        $_SESSION['correo_electronico'] = $correo_electronico;
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
?>
