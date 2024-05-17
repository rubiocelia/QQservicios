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
        header("Location: listaCoaches.php");
        exit;
    } elseif (isset($_POST['modificar'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $titulacion = $_POST['titulacion'];
        $descripcion = $_POST['descripcion'];
        $linkedin = $_POST['linkedin'];
        $video = $_POST['video'];
        $general = $_POST['general'];
        $foto = $_POST['foto'];

        $query = "UPDATE Coaches SET Nombre = ?, Apellidos = ?, Titulacion = ?, Descripcion = ?, LinkedIn = ?, Video = ?, General = ?, Foto = ? WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssi", $nombre, $apellidos, $titulacion, $descripcion, $linkedin, $video, $general, $foto, $idCoach);
        $stmt->execute();
        header("Location: listaCoaches.php");
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
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="miCuenta">
<?php include('menu_sesion_iniciada.php'); ?>
    <div class="form-container">
        <h1>Detalle del Coach</h1>
        <form method="post" class="styled-form">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" value="<?php echo htmlspecialchars($coach['Nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" class="form-input" value="<?php echo htmlspecialchars($coach['Apellidos']); ?>" required>
            </div>

            <div class="form-group">
                <label for="titulacion" class="form-label">Titulación:</label>
                <input type="text" id="titulacion" name="titulacion" class="form-input" value="<?php echo htmlspecialchars($coach['Titulacion']); ?>" required>
            </div>

            <div class="form-group">
                <label for="formacion" class="form-label">Descripcion:</label>
                <input type="text" id="formacion" name="formacion" class="form-input" value="<?php echo htmlspecialchars($coach['Descripcion']); ?>" required>
            </div>

            <div class="form-group">
                <label for="linkedin" class="form-label">LinkedIn:</label>
                <input type="url" id="linkedin" name="linkedin" class="form-input" value="<?php echo htmlspecialchars($coach['LinkedIn']); ?>" required>
            </div>

            <div class="form-group">
                <label for="video" class="form-label">Video:</label>
                <input type="url" id="video" name="video" class="form-input" value="<?php echo htmlspecialchars($coach['Video']); ?>" required>
            </div>

            <div class="form-group">
                <label for="general" class="form-label">General:</label>
                <input type="text" id="general" name="general" class="form-input" value="<?php echo htmlspecialchars($coach['General']); ?>" required>
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">Foto:</label>
                <input type="input" id="foto" name="foto" class="form-input" value="<?php echo htmlspecialchars($coach['Foto']); ?>" required>
            </div>

            <div class="form-button-container">
                <button type="submit" name="modificar" class="form-button">Modificar</button>
                <button type="submit" name="eliminar" class="form-button">Eliminar</button>
                <button type="submit" name="eliminar" class="form-button-cancel">Cancelar</button>
            </div>
        </form>
    </div>
    <?php include('footer.php'); ?>

</body>
</html>
