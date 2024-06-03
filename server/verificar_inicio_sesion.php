<?php
// Incluir archivo de conexión a la base de datos
include '../bbdd/conecta.php';

// Obtener datos del formulario
$correo_electronico = $_POST['correo_electronico'];
$contrasena = $_POST['contrasena'];

// Realizar la verificación de las credenciales
$conexion = getConexion();
$query = "SELECT u.ID, u.Correo_electronico, da.Contrasena, da.Administrador
          FROM Usuarios u
          INNER JOIN DatosAcceso da ON u.ID = da.ID_usuario
          WHERE u.Correo_electronico = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('s', $correo_electronico);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // Usuario encontrado, verificar contraseña
    $row = $resultado->fetch_assoc();
    $id_usuario = $row['ID']; // Obtenemos el ID del usuario
    $correo_electronico = $row['Correo_electronico'];
    $contrasena_almacenada = $row['Contrasena'];
    $administrador = $row['Administrador'];
    // Comparar la contraseña proporcionada con la almacenada
    if (password_verify($contrasena, $contrasena_almacenada)) {
        // Contraseña válida, iniciar sesión
        session_start();
        $_SESSION['id_usuario'] = $id_usuario; // Establecemos el ID del usuario en la sesión
        //Código para guardar la fecha de inicio de sesión del usuario
        $fecha_inicio = date('Y-m-d H:i:s');
        $_SESSION['inicio_sesion'] = $fecha_inicio;

        // Registrar el inicio de sesión en la tabla de sesiones
        $query_sesion = "INSERT INTO Sesiones (ID_usuario, FechaInicio, UltimoLatido) VALUES (?, ?, ?)";
        $stmt_sesion = $conexion->prepare($query_sesion);
        $stmt_sesion->bind_param('iss', $id_usuario, $fecha_inicio, $fecha_inicio);
        $stmt_sesion->execute();

        // Devolver respuesta JSON con éxito
        if ($administrador == 1) {
            echo json_encode(array("success" => true, "redirect" => "mi_cuenta_admin.php"));
        } else {
            echo json_encode(array("success" => true, "redirect" => ""));
        }
        exit();
    } else {
        // Contraseña incorrecta, devolver respuesta JSON con error
        echo json_encode(array("success" => false, "message" => "Error de credenciales"));
    }
} else {
    // Usuario no encontrado, devolver respuesta JSON con error
    echo json_encode(array("success" => false, "message" => "Error de credenciales"));
}
