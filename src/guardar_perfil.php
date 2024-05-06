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
        exit;
    }

    // Bind de parámetros por referencia
    $stmtUpdateUsuarios->bind_param("sssssi", $nombre, $apellidos, $email, $telefono, $organizacion, $id_usuario);

    // Ejecutar la consulta
    $successUpdateUsuarios = $stmtUpdateUsuarios->execute();

    if (!$successUpdateUsuarios) {
        $conexion->rollback(); // Si la actualización falla, deshacer la transacción
        exit;
    }

    // Si llegamos aquí, la actualización fue exitosa, confirmar la transacción
    $conexion->commit();

    // Cerrar la declaración preparada
    $stmtUpdateUsuarios->close();

    // Cerrar la conexión
    $conexion->close();
    exit;
}

// Asegúrate de verificar si se cargó una imagen
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['foto']['tmp_name'];
    $nombreArchivo = basename($_FILES['foto']['name']);
    $rutaGuardar = './path_to_save/' . $nombreArchivo; // Asegúrate de especificar el camino correcto y tener permisos de escritura

    if (move_uploaded_file($tmp_name, $rutaGuardar)) {
        // Si la imagen se guardó exitosamente, actualiza la ruta en la base de datos
        $sqlUpdateFoto = "UPDATE Usuarios SET Foto=? WHERE ID=?";
        $stmtUpdateFoto = $conexion->prepare($sqlUpdateFoto);
        $stmtUpdateFoto->bind_param("si", $rutaGuardar, $id_usuario);
        $stmtUpdateFoto->execute();
        $stmtUpdateFoto->close();
    } else {
        echo "Error al subir el archivo.";
        $conexion->rollback(); // Si la carga falla, deshacer la transacción
        $conexion->close();
        exit;
    }
}

// Continúa con el resto de la actualización de datos...

?>