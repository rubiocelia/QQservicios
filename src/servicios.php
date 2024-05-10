<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/Servicios.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="index">
    <?php include('menu.php'); ?>
    <?php include('./bbdd/conecta.php'); ?>
    <?php $conn = getConexion(); ?>

    <main>
        <div class="fondo">
            <div class="parrfInicial">
                <h1 class="titulo">NUESTROS SERVICIOS</h1>
                <p class="txtInicial">
                    ¡Explora nuevas habilidades con nuestros cursos introductorios! <br>
                    ¿Listo para descubrir un mundo de posibilidades? Nuestros cursos te 
                    brindan el punto de partida perfecto para adquirir nuevas habilidades 
                    y abrirte a nuevas oportunidades. ¡Inscríbete hoy y da el primer paso 
                    hacia el éxito!
                </p>
            </div>
        </div>

        <div class="info">
            <h1>DESPIERTA TODO TU POTENCIAL AL EXPLORAR LO  QUE OFRECEN NUESTROS SERVICIOS. 
            </h1>
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