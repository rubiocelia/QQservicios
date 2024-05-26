<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nuevo Contenido</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/galeria.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>
<body>
<?php include('menu_sesion_iniciada.php'); ?>
    <h1>Añadir Nuevo Contenido</h1>
    <form action="procesar_nuevo_contenido.php" method="post" enctype="multipart/form-data">
        <label for="tipo">Tipo de Contenido:</label>
        <select name="tipo" id="tipo" required>
            <option value="foto">Foto</option>
            <option value="video_local">Video Local</option>
            <option value="video_youtube">Video de YouTube</option>
        </select>

        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" required>

        <div id="fotoInput" style="display: none;">
            <label for="foto">Seleccionar Foto:</label>
            <input type="file" name="foto" id="foto">
        </div>

        <div id="videoLocalInput" style="display: none;">
            <label for="video_local">Seleccionar Video:</label>
            <input type="file" name="video_local" id="video_local">
        </div>

        <div id="videoYoutubeInput" style="display: none;">
            <label for="url_youtube">URL de YouTube:</label>
            <input type="url" name="url_youtube" id="url_youtube">
        </div>

        <button type="submit">Añadir Contenido</button>
    </form>
    <button type="button" class="volver" onclick="window.history.back()">Volver</button>

    <script>
        document.getElementById('tipo').addEventListener('change', function () {
            var tipo = this.value;
            document.getElementById('fotoInput').style.display = 'none';
            document.getElementById('videoLocalInput').style.display = 'none';
            document.getElementById('videoYoutubeInput').style.display = 'none';

            if (tipo === 'foto') {
                document.getElementById('fotoInput').style.display = 'block';
            } else if (tipo === 'video_local') {
                document.getElementById('videoLocalInput').style.display = 'block';
            } else if (tipo === 'video_youtube') {
                document.getElementById('videoYoutubeInput').style.display = 'block';
            }
        });
    </script>
    <?php include('footer.php'); ?>
</body>
</html>
