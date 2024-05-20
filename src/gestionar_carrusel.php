<?php
session_start();
include('./bbdd/conecta.php');
$conn = getConexion();

if (!isset($_GET['id'])) {
    die("ID de carrusel no proporcionado");
}

$idCarrusel = $_GET['id'];

// Obtener el nombre del carrusel
$query = "SELECT Nombre_carrusel FROM carruselMultimedia WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idCarrusel);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró el carrusel");
}

$carrusel = $result->fetch_assoc();
$nombreCarrusel = $carrusel['Nombre_carrusel'];

$queryElementos = "SELECT * FROM carruselMultimedia WHERE Nombre_carrusel = ?";
$stmtElementos = $conn->prepare($queryElementos);
$stmtElementos->bind_param("s", $nombreCarrusel);
$stmtElementos->execute();
$resultElementos = $stmtElementos->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Carrusel</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/estilos.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
    <?php include('menu_sesion_iniciada.php'); ?>
    <button type="button" class="volver" onclick="window.location.href = 'mi_cuenta_admin.php';">⬅​ Volver</button>
    <div class="form-container">
        <h1>Gestionar Carrusel: <?php echo htmlspecialchars($nombreCarrusel); ?></h1>

        <form id="formNuevoElemento" class="styled-form" enctype="multipart/form-data">
            <input type="hidden" name="nombre_carrusel" value="<?php echo htmlspecialchars($nombreCarrusel); ?>">
            <div class="form-group">
                <label for="archivo" class="form-label">Seleccionar archivo:</label>
                <input type="file" id="archivo" name="archivo" class="form-input" accept="image/*,video/*">
            </div>

            <div class="form-group">
                <label for="link_video" class="form-label">URL de YouTube:</label>
                <input type="url" id="link_video" name="link_video" class="form-input">
            </div>

            <div class="form-button-container">
                <button type="button" onclick="agregarElemento()" class="form-button">Agregar Elemento</button>
            </div>
        </form>

        <h2>Elementos del Carrusel</h2>
        <div id="elementosCarrusel" class="elementos-container">
            <?php while ($elemento = $resultElementos->fetch_assoc()) : ?>
                <div class="elemento-item">
                    <?php if ($elemento['RutaArchivos']) : ?>
                        <?php $extension = pathinfo($elemento['RutaArchivos'], PATHINFO_EXTENSION); ?>
                        <?php if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) : ?>
                            <img src="<?php echo htmlspecialchars($elemento['RutaArchivos']); ?>" alt="">
                        <?php elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) : ?>
                            <video width="560" height="315" controls>
                                <source src="<?php echo htmlspecialchars($elemento['RutaArchivos']); ?>" type="video/<?php echo $extension; ?>">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        <?php endif; ?>
                    <?php elseif ($elemento['Link_Video']) : ?>
                        <?php
                        // Convertir URL de YouTube a URL de inserción
                        $videoUrl = str_replace("watch?v=", "embed/", htmlspecialchars($elemento['Link_Video']));
                        ?>
                        <iframe width="560" height="315" src="<?php echo $videoUrl; ?>" frameborder="0" allowfullscreen></iframe>
                    <?php endif; ?>
                    <button type="button" class="btn-eliminar" onclick="eliminarElemento(<?php echo $elemento['ID']; ?>)">Eliminar</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include('footer.php'); ?>

    <script>
        function agregarElemento() {
            var formData = new FormData(document.getElementById('formNuevoElemento'));

            fetch('./server/agregar_elemento.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        Swal.fire('Error', 'Error al agregar el elemento: ' + data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function eliminarElemento(id) {
            fetch('./server/eliminar_elemento.php?id=' + id, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        Swal.fire('Error', 'Error al eliminar el elemento: ' + data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>