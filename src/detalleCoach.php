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
        $formacion = $_POST['formacion'];
        $experiencia = $_POST['experiencia'];
        $linkedin = $_POST['linkedin'];
        $video = $_POST['video'];
        $general = $_POST['general'];
        $foto = $_POST['foto'];

        $query = "UPDATE Coaches SET Nombre = ?, Apellidos = ?, Titulacion = ?, Formacion = ?, Experiencia = ?, LinkedIn = ?, Video = ?, General = ?, Foto = ? WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssi", $nombre, $apellidos, $titulacion, $formacion, $experiencia, $linkedin, $video, $general, $foto, $idCoach);
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
    <title>Detalle del Coach</title>
</head>
<body>
    <h1>Detalle del Coach</h1>
    <form method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($coach['Nombre']); ?>" required>
        
        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($coach['Apellidos']); ?>" required>
        
        <label for="titulacion">Titulación:</label>
        <input type="text" id="titulacion" name="titulacion" value="<?php echo htmlspecialchars($coach['Titulacion']); ?>" required>
        
        <label for="formacion">Formación:</label>
        <input type="text" id="formacion" name="formacion" value="<?php echo htmlspecialchars($coach['Formacion']); ?>" required>
        
        <label for="experiencia">Experiencia:</label>
        <input type="text" id="experiencia" name="experiencia" value="<?php echo htmlspecialchars($coach['Experiencia']); ?>" required>
        
        <label for="linkedin">LinkedIn:</label>
        <input type="url" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($coach['LinkedIn']); ?>" required>
        
        <label for="video">Video:</label>
        <input type="url" id="video" name="video" value="<?php echo htmlspecialchars($coach['Video']); ?>" required>
        
        <label for="general">General:</label>
        <input type="text" id="general" name="general" value="<?php echo htmlspecialchars($coach['General']); ?>" required>
        
        <label for="foto">Foto (URL):</label>
        <input type="text" id="foto" name="foto" value="<?php echo htmlspecialchars($coach['Foto']); ?>" required>
        
        <button type="submit" name="modificar">Modificar</button>
        <button type="submit" name="eliminar">Eliminar</button>
    </form>
</body>
</html>
