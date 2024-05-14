<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/Servicios.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body>
    <?php include_once('./bbdd/conecta.php'); ?>
    <?php $conn = getConexion(); ?>

    <main>
        <div class="cardsCoaches">

            <div class="contenido-list">
                <h2>Contenidos del Producto</h2>
                <?php
                // Consultar los contenidos del producto con ID 1
                $contenidoQuery = "SELECT Titulo, Descripcion FROM Contenidos WHERE ID_Producto = 1";
                $contenidoResult = $conn->query($contenidoQuery);

                if ($contenidoResult->num_rows > 0) {
                    while ($fila = $contenidoResult->fetch_assoc()) {
                        echo "<div class='contenido'>";
                        echo "<h3>" . $fila["Titulo"] . "</h3>";
                        echo "<div>" . $fila["Descripcion"] . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "No se encontraron contenidos para el producto con ID 1.";
                }
                ?>
            </div>

            <div class="testimonio-list">
                <h2>Testimonios del Producto</h2>
                <?php
                // Consultar los testimonios del producto con ID 1
                $testimonioQuery = "SELECT Nombre, Subtitulo, Descripcion, Foto FROM Testimonios WHERE ID_Producto = 1";
                $testimonioResult = $conn->query($testimonioQuery);

                if ($testimonioResult->num_rows > 0) {
                    while ($fila = $testimonioResult->fetch_assoc()) {
                        echo "<div class='testimonio'>";
                        echo "<h3>" . $fila["Nombre"] . "</h3>";
                        echo "<h4>" . $fila["Subtitulo"] . "</h4>";
                        echo "<div>" . $fila["Descripcion"] . "</div>";
                        if (!empty($fila["Foto"])) {
                            echo "<img src='" . $fila["Foto"] . "' alt='Foto de " . $fila["Nombre"] . "'>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "No se encontraron testimonios para el producto con ID 1.";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>

</html>