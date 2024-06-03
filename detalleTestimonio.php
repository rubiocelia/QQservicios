<?php
session_start();
include('./bbdd/conecta.php');
$conn = getConexion();

if (!isset($_GET['id'])) {
    die("ID de testimonio no proporcionado");
}

$idTestimonio = $_GET['id'];

// Obtener los detalles del testimonio
$query = "SELECT * FROM Testimonios WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idTestimonio);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró el testimonio");
}

$testimonio = $result->fetch_assoc();

// Procesar la eliminación o modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $query = "DELETE FROM Testimonios WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idTestimonio);
        $stmt->execute();
        header("Location: mi_cuenta_admin.php");
        exit;
    } elseif (isset($_POST['modificar'])) {
        $updates = [];
        $params = [];
        $types = '';

        if (!empty($_POST['nombre'])) {
            $updates[] = "Nombre = ?";
            $params[] = $_POST['nombre'];
            $types .= 's';
        }
        if (!empty($_POST['subtitulo'])) {
            $updates[] = "Subtitulo = ?";
            $params[] = $_POST['subtitulo'];
            $types .= 's';
        }
        if (!empty($_POST['descripcion'])) {
            $updates[] = "Descripcion = ?";
            $params[] = $_POST['descripcion'];
            $types .= 's';
        }
        if (!empty($_POST['producto'])) {
            $updates[] = "ID_Producto = ?";
            $params[] = $_POST['producto'];
            $types .= 'i';
        }

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = './archivos/testimonios/';
            $fileName = uniqid() . '_' . basename($_FILES['foto']['name']);
            $uploadFile = $uploadDir . $fileName;
            $RutaFile = './archivos/testimonios/' . $fileName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                $updates[] = "Foto = ?";
                $params[] = $RutaFile;
                $types .= 's';
            } else {
                die("Error al subir el archivo.");
            }
        }

        if (!empty($updates)) {
            $query = "UPDATE Testimonios SET " . implode(", ", $updates) . " WHERE ID = ?";
            $params[] = $idTestimonio;
            $types .= 'i';

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }

        header("Location: detalleTestimonio.php?id=$idTestimonio");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Testimonio</title>
    <link rel="stylesheet" type="text/css" href="./estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="./estilos/css/miCuenta_admin.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
    <?php include('menu_sesion_iniciada.php'); ?>
    <button type="button" class="volver" onclick="window.location.href = 'mi_cuenta_admin.php';">⬅️ Volver</button>
    <div class="form-container">
        <h1>Detalle del Testimonio</h1>
        <form method="post" class="styled-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input"
                    value="<?php echo htmlspecialchars($testimonio['Nombre']); ?>">
            </div>

            <div class="form-group">
                <label for="subtitulo" class="form-label">Subtitulo:</label>
                <input type="text" id="subtitulo" name="subtitulo" class="form-input"
                    value="<?php echo htmlspecialchars($testimonio['Subtitulo']); ?>">
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion"
                    class="form-input"><?php echo htmlspecialchars($testimonio['Descripcion']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="producto" class="form-label">Producto Asociado:</label>
                <select id="producto" name="producto" class="form-input">
                    <option value="">-- Seleccionar Producto --</option>
                    <?php
                    $productos = $conn->query("SELECT ID, Nombre FROM Productos");
                    while ($producto = $productos->fetch_assoc()) {
                        $selected = $producto['ID'] == $testimonio['ID_Producto'] ? 'selected' : '';
                        echo "<option value='" . $producto['ID'] . "' $selected>" . $producto['Nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="foto" class="form-label">Foto:</label>
                <input type="file" id="foto" name="foto" class="form-input" accept="image/*">
                <?php if (!empty($testimonio['Foto'])): ?>
                <img src="<?php echo htmlspecialchars($testimonio['Foto']); ?>" alt="Foto del testimonio"
                    style="width:100px;">
                <?php endif; ?>
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