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
    <button type="button" class="volver" onclick="window.location.href='formulario_nuevo_contenido.php'">Añadir Nuevo Contenido</button>
    <?php include('footer.php'); ?>
</body>

</html>