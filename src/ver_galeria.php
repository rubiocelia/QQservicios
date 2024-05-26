<?php
require_once("./bbdd/conecta.php");

// Obtener conexión a la base de datos
$conexion = getConexion();

// Obtener ID de la galería
$idGaleria = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi galería</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/galeria.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1>Contenido de la Galería</h1>
    <button type="button" class="volver" onclick="window.history.back()">Volver</button>
    <?php
    $conexion = getConexion();

    // Consulta para obtener el contenido de la galería
    $query = "SELECT c.ID, c.Tipo, c.URL_Local, c.URL_Youtube, c.Descripcion, gc.Orden 
              FROM ContenidoMultimedia c
              JOIN GaleriaContenido gc ON c.ID = gc.ID_Contenido
              WHERE gc.ID_Galeria = ?
              ORDER BY gc.Orden";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idGaleria);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Comprobar si hay resultados
    if ($resultado->num_rows > 0) {
        echo '<div class="TablaFondo">';
        echo '<table class="contenido-table" id="contenidoTable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Tipo</th>';
        echo '<th>Descripción</th>';
        echo '<th>Contenido</th>';
        echo '<th>Acciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        // Iterar sobre los resultados y mostrar cada contenido en una fila de la tabla
        while ($contenido = $resultado->fetch_assoc()) {
            echo '<tr id="contenido_' . $contenido['ID'] . '">';
            echo '<td>' . htmlspecialchars($contenido['Tipo']) . '</td>';
            echo '<td>' . htmlspecialchars($contenido['Descripcion']) . '</td>';
            echo '<td>';
            if ($contenido['Tipo'] == 'foto') {
                echo '<img src="' . htmlspecialchars($contenido['URL_Local']) . '" alt="' . htmlspecialchars($contenido['Descripcion']) . '" style="max-width: 200px; max-height: 200px;">';
            } elseif ($contenido['Tipo'] == 'video_local') {
                echo '<video width="320" height="240" controls>
                        <source src="' . htmlspecialchars($contenido['URL_Local']) . '" type="video/mp4">
                      Your browser does not support the video tag.
                      </video>';
            } elseif ($contenido['Tipo'] == 'video_youtube') {
                $youtubeEmbedUrl = str_replace("watch?v=", "embed/", htmlspecialchars($contenido['URL_Youtube']));
                echo '<iframe width="320" height="240" src="' . $youtubeEmbedUrl . '" frameborder="0" allowfullscreen></iframe>';
            }
            echo '</td>';
            echo '<td><button class="btn-eliminar" onclick="eliminarContenido(' . $contenido['ID'] . ')">Eliminar</button></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo 'No se encontró contenido en esta galería.';
    }
    ?>
    <button onclick="añadirContenido(<?php echo $idGaleria; ?>)" class="volver">Añadir Contenido</button>

    <script>
        function eliminarContenido(idContenido) {
            if (confirm("¿Estás seguro de que quieres eliminar este contenido?")) {
                window.location.href = './server/eliminar_contenido.php?id=' + idContenido;
            }
        }

        function añadirContenido(idGaleria) {
            window.location.href = './añadir_contenido.php?id_galeria=' + idGaleria;
        }

        // Código para manejar el drag and drop y reordenar el contenido
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('contenidoTable');
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            let draggingElement;
            let placeholder = document.createElement('tr');
            placeholder.className = 'placeholder';

            rows.forEach(row => {
                row.draggable = true;

                row.addEventListener('dragstart', function(e) {
                    draggingElement = row;
                    e.dataTransfer.effectAllowed = 'move';
                });

                row.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    const target = e.target.closest('tr');
                    if (target && target !== draggingElement) {
                        const rect = target.getBoundingClientRect();
                        const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                        table.tBodies[0].insertBefore(placeholder, next && target.nextSibling || target);
                    }
                });

                row.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (placeholder.parentNode) {
                        table.tBodies[0].insertBefore(draggingElement, placeholder);
                        placeholder.remove();
                    }
                });

                row.addEventListener('dragend', function() {
                    placeholder.remove();
                    actualizarOrden();
                });
            });
        });

        function actualizarOrden() {
            const rows = document.querySelectorAll('#contenidoTable tbody tr');
            let orden = [];
            rows.forEach((row, index) => {
                const id = row.id.split('_')[1];
                orden.push({
                    id,
                    orden: index + 1
                });
            });

            fetch('./server/actualizar_orden.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        orden
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Orden actualizado correctamente', '', 'success');
                    } else {
                        Swal.fire('Error al actualizar el orden', '', 'error');
                    }
                });
        }
    </script>
    <?php include('footer.php'); ?>
</body>

</html>