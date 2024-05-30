<?php
require_once("./bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener ID de la galería
$idGaleria = $_GET['id_galeria'];

// Consulta para obtener todo el contenido que no está en la galería
$query = "
    SELECT ID, Tipo, URL_Local, URL_Youtube, Descripcion 
    FROM ContenidoMultimedia 
    WHERE ID NOT IN (
        SELECT ID_Contenido 
        FROM GaleriaContenido 
        WHERE ID_Galeria = ?
    )
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idGaleria);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Contenido a Galería</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/galeria.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body>
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1>Añadir Contenido a Galería</h1>
    <form action="procesar_anadir_contenido.php" method="post">
        <input type="hidden" name="id_galeria" value="<?php echo $idGaleria; ?>">
        <?php
        if ($resultado->num_rows > 0) {
            echo '<div class="TablaFondo">';
            echo '<table class="contenido-table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Seleccionar</th>';
            echo '<th>Tipo</th>';
            echo '<th>Descripción</th>';
            echo '<th>Contenido</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($contenido = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td><input type="checkbox" name="contenidos[]" value="' . $contenido['ID'] . '"></td>';
                echo '<td>' . htmlspecialchars($contenido['Tipo']) . '</td>';
                echo '<td>' . htmlspecialchars($contenido['Descripcion']) . '</td>';
                echo '<td>';
                if ($contenido['Tipo'] == 'foto') {
                    echo '<img src="' . htmlspecialchars($contenido['URL_Local']) . '" alt="' . htmlspecialchars($contenido['Descripcion']) . '" style="max-width: 100px; max-height: 100px;">';
                } elseif ($contenido['Tipo'] == 'video_local') {
                    echo '<video width="160" height="120" controls>
                            <source src="' . htmlspecialchars($contenido['URL_Local']) . '" type="video/mp4">
                          Your browser does not support the video tag.
                          </video>';
                } elseif ($contenido['Tipo'] == 'video_youtube') {
                    $youtubeEmbedUrl = str_replace("watch?v=", "embed/", htmlspecialchars($contenido['URL_Youtube']));
                    echo '<iframe width="160" height="120" src="' . $youtubeEmbedUrl . '" frameborder="0" allowfullscreen></iframe>';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo 'No se encontró contenido.';
        }
        ?>
        <button type="submit" class="volver">Añadir Contenido Seleccionado</button>
    </form>
    <button type="button" class="volver" onclick="window.history.back()">Volver</button>
    <button type="button" class="volver" onclick="mostrarFormulario()">Añadir Nuevo Contenido</button>

    <div id="formularioNuevoContenido" class="form-container" style="display: none;">
        <form id="formNuevoContenido" class="styled-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo" class="form-label">Tipo de Contenido:</label>
                <select id="tipo" name="tipo" class="form-input" required>
                    <option value="foto">Foto</option>
                    <option value="video_local">Video Local</option>
                    <option value="video_youtube">Video de YouTube</option>
                </select>
            </div>
            <div class="form-group" id="campoLocal" style="display: none;">
                <label for="url_local" class="form-label">Archivo Local:</label>
                <input type="file" id="url_local" name="url_local" class="form-input">
            </div>
            <div class="form-group" id="campoYoutube" style="display: none;">
                <label for="url_youtube" class="form-label">URL de YouTube:</label>
                <input type="url" id="url_youtube" name="url_youtube" class="form-input">
            </div>
            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-input" required></textarea>
            </div>
            <button type="button" onclick="subirContenido()" class="form-button">Subir Contenido</button>
        </form>
    </div>
    <?php include('footer.php'); ?>
</body>
<script>
    function mostrarFormulario() {
        document.getElementById('formularioNuevoContenido').style.display = 'block';
        mostrarCampoCorrespondiente();
    }

    document.getElementById('tipo').addEventListener('change', mostrarCampoCorrespondiente);

    function mostrarCampoCorrespondiente() {
        var tipo = document.getElementById('tipo').value;
        var campoLocal = document.getElementById('campoLocal');
        var campoYoutube = document.getElementById('campoYoutube');
        campoLocal.style.display = 'none';
        campoYoutube.style.display = 'none';
        if (tipo === 'foto' || tipo === 'video_local') {
            campoLocal.style.display = 'block';
        } else if (tipo === 'video_youtube') {
            campoYoutube.style.display = 'block';
        }
    }

    function subirContenido() {
        var formData = new FormData(document.getElementById('formNuevoContenido'));
        fetch('subir_contenido.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Mostrar el campo correspondiente basado en la selección inicial
    document.addEventListener('DOMContentLoaded', function() {
        mostrarCampoCorrespondiente();
    });
</script>

</html>