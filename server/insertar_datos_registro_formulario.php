<?php
// Incluir archivo de conexión a la base de datos
include '../bbdd/conecta.php';

// Obtener datos del formulario
$nombre = $_POST['Nombre'];
$apellidos = $_POST['Apellidos'];
$correo = $_POST['correo_electronico'];
$telefono = $_POST['NumTel'];
$organizacion = $_POST['Organizacion'];
$contrasena = $_POST['contrasena']; // Se obtiene la contraseña del formulario
$conexion = getConexion();

// Preparar la consulta SQL para insertar en la tabla Usuarios
$sql_usuarios = "INSERT INTO Usuarios (Nombre, Apellidos, Correo_electronico, Numero_telefono,Organizacion, Foto, Fecha_Registro)
VALUES ('$nombre', '$apellidos', '$correo', '$telefono','$organizacion', './archivos/perfil/fotosPerfil/fotoPorDefecto.jpg', NOW())";

// Realizar la inserción en la tabla Usuarios
if ($conexion->query($sql_usuarios) === TRUE) {
    // Obtenemos el ID del usuario recién insertado
    $id_usuario = $conexion->insert_id;

    // Preparar la consulta SQL para insertar en la tabla DatosAcceso
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT); // Se encripta la contraseña
    $sql_datos_acceso = "INSERT INTO DatosAcceso (ID_usuario, Contrasena, Administrador, FechaLogin)
                         VALUES ('$id_usuario', '$hashed_password', 0, NOW())";

    // Realizar la inserción en la tabla DatosAcceso
    if ($conexion->query($sql_datos_acceso) === TRUE) {
        // Inicio de sesión (si es necesario)
        session_start();
        $_SESSION['id_usuario'] = $id_usuario; // Establecemos el ID del usuario en la sesión

        // Redirigir al usuario a su cuenta
        header("Location: ../mi_cuenta.php");
        exit(); // Finalizar el script para evitar ejecución adicional
    } else {
        // Error al insertar en DatosAcceso
        echo "Error al insertar en DatosAcceso: " . $conexion->error;
    }
} else {
    // Error al insertar en Usuarios
    echo "Error al insertar en Usuarios: " . $conexion->error;
}

// Cerrar conexión
$conexion->close();
?>