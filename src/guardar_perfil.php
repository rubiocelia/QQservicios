<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
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
    $id_usuario = $_SESSION['id_usuario'];

    // Iniciar una transacción
    $conexion->begin_transaction();

    // SQL para actualizar los datos del usuario en la tabla Usuarios
    $sqlUpdateUsuarios = "UPDATE Usuarios SET Nombre=?, Apellidos=?, Correo_electronico=?, Numero_telefono=?, Organizacion=? WHERE ID=?";
    $stmtUpdateUsuarios = $conexion->prepare($sqlUpdateUsuarios);
    if ($stmtUpdateUsuarios === false) {
        echo json_encode(['success' => false, 'message' => 'Error de preparación SQL para actualizar Usuarios: ' . $conexion->error]);
        exit;
    }

    // Bind de parámetros por referencia
    $stmtUpdateUsuarios->bind_param("sssssi", $nombre, $apellidos, $email, $telefono, $organizacion, $id_usuario);

    // Ejecutar la consulta
    $successUpdateUsuarios = $stmtUpdateUsuarios->execute();

    if (!$successUpdateUsuarios) {
        $conexion->rollback(); // Si la actualización falla, deshacer la transacción
        echo json_encode(['success' => false, 'message' => 'Error al actualizar datos en Usuarios: ' . $stmtUpdateUsuarios->error]);
        exit;
    }

    // Si llegamos aquí, la actualización fue exitosa, confirmar la transacción
    $conexion->commit();

    // Cerrar la declaración preparada
    $stmtUpdateUsuarios->close();

    // Cerrar la conexión
    $conexion->close();

    echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
    exit;
}
?>