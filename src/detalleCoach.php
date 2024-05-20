<?php
session_start();
include('./bbdd/conecta.php');
$conn = getConexion();

if (!isset($_GET['id'])) {
    die("ID de coach no proporcionado");
}

$idCoach = $_GET['id'];

// Obtener los detalles del coach
$query = "SELECT * FROM Coaches WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idCoach);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró el coach");
}

$coach = $result->fetch_assoc();

// Procesar la eliminación o modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $query = "DELETE FROM Coaches WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idCoach);
        $stmt->execute();
        header("Location: mi_cuenta_admin.php");
        exit;
    } elseif (isset($_POST['modificar'])) {
        $updates = [];
        $params = [];
        $types = '';

        // Manejo de cada campo de forma opcional
        if (!empty($_POST['nombre'])) {
            $updates[] = "Nombre = ?";
            $params[] = $_POST['nombre'];
            $types .= 's';
        }
        if (!empty($_POST['apellidos'])) {
            $updates[] = "Apellidos = ?";
            $params[] = $_POST['apellidos'];
            $types .= 's';
        }
        if (!empty($_POST['titulacion'])) {
            $updates[] = "Titulacion = ?";
            $params[] = $_POST['titulacion'];
            $types .= 's';
        }
        if (!empty($_POST['descripcion'])) {
            $updates[] = "Descripcion = ?";
            $params[] = $_POST['descripcion'];
            $types .= 's';
        }
        if (!empty($_POST['linkedin'])) {
            $updates[] = "LinkedIn = ?";
            $params[] = $_POST['linkedin'];
            $types .= 's';
        }
        if (!empty($_POST['video'])) {
            $updates[] = "Video = ?";
            $params[] = $_POST['video'];
            $types .= 's';
        }
        if (!empty($_POST['general'])) {
            $updates[] = "General = ?";
            $params[] = $_POST['general'];
            $types .= 's';
        }

        // Manejo de la subida de la foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = './archivos/coaches/';
            $fileName = uniqid('coach_', true) . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $uploadFile = $uploadDir . $fileName;
            $RutaFile = './archivos/coaches/' . $fileName;

            // Validar el tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['foto']['type'], $allowedTypes)) {
                die("Formato de archivo no permitido. Solo se permiten archivos JPEG, PNG y GIF.");
            }

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                $updates[] = "Foto = ?";
                $params[] = $RutaFile;
                $types .= 's';
            } else {
                die("Error al subir el archivo.");
            }
        }

        if (!empty($updates)) {
            $query = "UPDATE Coaches SET " . implode(", ", $updates) . " WHERE ID = ?";
            $params[] = $idCoach;
            $types .= 'i';

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }

        header("Location: detalleCoach.php?id=$idCoach");
        exit;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="stylesheet" type="text/css" href="path/to/your/estilos.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
    <?php include('menu_sesion_iniciada.php'); ?>
    <button type="button" class="volver" onclick="window.location.href = 'mi_cuenta_admin.php';">⬅​ Volver</button>
    <div class="form-container">
        <h1>Detalle del Coach</h1>
        <form method="post" class="styled-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo htmlspecialchars($coach['Nombre']); ?>">
            </div>

            <div class="form-group">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" class="form-input" value="<?php echo htmlspecialchars($coach['Apellidos']); ?>">
            </div>

            <div class="form-group">
                <label for="titulacion" class="form-label">Titulo profesional:</label>
                <input type="text" id="titulacion" name="titulacion" class="form-input" value="<?php echo htmlspecialchars($coach['Titulacion']); ?>">
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" class="form-input" value="<?php echo htmlspecialchars($coach['Descripcion']); ?>">
            </div>

            <div class="form-group">
                <label for="linkedin" class="form-label">LinkedIn:</label>
                <input type="text" id="linkedin" name="linkedin" class="form-input" value="<?php echo htmlspecialchars($coach['LinkedIn']); ?>">
            </div>

            <div class="form-group">
                <label for="video" class="form-label">Video:</label>
                <input type="text" id="video" name="video" class="form-input" value="<?php echo htmlspecialchars($coach['Video']); ?>">
            </div>

            <div class="form-group">
                <label for="general" class="form-label">General:</label>
                <input type="text" id="general" name="general" class="form-input" value="<?php echo htmlspecialchars($coach['General']); ?>">
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" id="foto" name="foto" class="form-input" accept="image/*">
            </div>

            <div class="form-button-container">
                <button type="submit" name="modificar" class="form-button">Modificar</button>
                <button type="submit" name="eliminar" class="form-button">Eliminar</button>
                <button type="button" onclick="window.history.back()" class="form-button-cancel">Cancelar</button>
            </div>
        </form>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>