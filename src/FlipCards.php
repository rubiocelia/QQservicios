<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/coaches.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="index">
    <?php include('menu.php'); ?>
    <?php include('./bbdd/conecta.php'); ?>
    <?php $conn = getConexion(); ?>

    <main>
        <div class="fondo">
            <div class="parrfInicial">
                <h1 class="titulo">NUESTROS COACHES</h1>
                <p class="txtInicial">
                    En nuestra comunidad, creemos que la calidad del aprendizaje depende significativamente
                    de la excelencia de los instructores. Por eso, hemos reunido a un equipo de coaches
                    altamente calificados y apasionados, dedicados a ayudarte a alcanzar tus metas personales
                    y profesionales.
                </p>
            </div>
        </div>

        <div class="info">
            <h1>Desliza a través de los perfiles de nuestros coaches para descubrir más sobre su
                experiencia y áreas de especialización. Haz clic en su foto para aprender más sobre
                su enfoque y cómo pueden ayudarte a lograr tus objetivos.</h1>
        </div>

        <div class="cardsCoaches">
            <?php
            $query = "SELECT * FROM Coaches";
            $result = $conn->query($query);
            while ($coach = $result->fetch_assoc()) {
                echo '<div class="card">
                        <div class="face front">
                            <img src="' . $coach['Foto'] . '" alt="' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '">
                            <h3>' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '</h3>
                        </div>
                        <div class="face back">
                            <h3>' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '</h3>
                            <p>' . $coach['Experiencia'] . '</p>
                            <div class="link">
                                <a href="#">Conocer más</a>
                            </div>
                        </div>
                    </div>';
            }
            ?>
        </div>
    </main>

    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <?php include('footer.php'); ?>
</body>

</html>