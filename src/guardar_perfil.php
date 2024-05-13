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

    // Obtener el ID de usuario
    $id_usuario = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id_usuario'];

    // Iniciar una transacción
    $conexion->begin_transaction();

    // SQL para actualizar los datos del usuario en la tabla Usuarios
    $sqlUpdateUsuarios = "UPDATE Usuarios SET Nombre=?, Apellidos=?, Correo_electronico=?, Numero_telefono=?, Organizacion=? WHERE ID=?";
    $stmtUpdateUsuarios = $conexion->prepare($sqlUpdateUsuarios);
    if ($stmtUpdateUsuarios === false) {
        echo json_encode(['success' => false, 'message' => 'Error de preparación SQL para actualizar Usuarios: ' . $conexion->error]);
        $conexion->close();
        exit;
    }

    // Bind de parámetros por referencia
    $stmtUpdateUsuarios->bind_param("sssssi", $nombre, $apellidos, $email, $telefono, $organizacion, $id_usuario);

    // Ejecutar la consulta
    if (!$stmtUpdateUsuarios->execute()) {
        $conexion->rollback(); // Si la actualización falla, deshacer la transacción
        echo json_encode(['success' => false, 'message' => 'Error al actualizar datos en Usuarios: ' . $stmtUpdateUsuarios->error]);
        $stmtUpdateUsuarios->close();
        $conexion->close();
        exit;
    }

    // Asegúrate de verificar si se cargó una imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        // Ruta donde se guardarán las imágenes
        $rutaImagenes = './archivos/perfil/fotosPerfil/';
        $archivoFoto = $rutaImagenes . basename($_FILES['foto']['name']);
        
        // Obtén la extensión del archivo para verificar si es una imagen
        $tipoArchivo = strtolower(pathinfo($archivoFoto, PATHINFO_EXTENSION));
        
        // Lista de extensiones permitidas
        $extensionesPermitidas = array("jpg", "jpeg", "png", "gif");
        
        if (in_array($tipoArchivo, $extensionesPermitidas)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $archivoFoto)) {
                // Actualizar la ruta de la foto en la base de datos
                $sqlUpdateFoto = "UPDATE Usuarios SET Foto=? WHERE ID=?";
                $stmtUpdateFoto = $conexion->prepare($sqlUpdateFoto);
                if ($stmtUpdateFoto === false) {
                    echo json_encode(['success' => false, 'message' => 'Error de preparación SQL para actualizar la foto: ' . $conexion->error]);
                    $conexion->close();
                    exit;
                }

                $stmtUpdateFoto->bind_param("si", $archivoFoto, $id_usuario);

                if (!$stmtUpdateFoto->execute()) {
                    $conexion->rollback(); // Si la actualización falla, deshacer la transacción
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar la foto en Usuarios: ' . $stmtUpdateFoto->error]);
                    $stmtUpdateFoto->close();
                    $conexion->close();
                    exit;
                }
                $stmtUpdateFoto->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir el archivo de imagen.']);
                $conexion->rollback();
                $conexion->close();
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Formato de archivo no permitido. Solo se permiten imágenes JPG, JPEG, PNG, y GIF.']);
            $conexion->rollback();
            $conexion->close();
            exit;
        }
    }

    // Si llegamos aquí, la actualización fue exitosa, confirmar la transacción
    $conexion->commit();
    echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
    
    // Cerrar la declaración preparada y la conexión
    $stmtUpdateUsuarios->close();
    $conexion->close();
    exit;
}
?>
