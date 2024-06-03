<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="./estilos/css/Servicios.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="index">
    <?php
    // Inicia o continúa una sesión existente
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica si la sesión está iniciada y si $id_usuario está definido
    if (isset($_SESSION['id_usuario'])) {
        // Incluye el menú para sesión iniciada
        include('menu_sesion_iniciada.php');
    } else {
        // Incluye el menú estándar
        include('menu.php');
    }
    ?>
    <?php include_once('./bbdd/conecta.php'); ?>
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
            <h1>DESPIERTA TODO TU POTENCIAL AL EXPLORAR LO QUE OFRECEN NUESTROS SERVICIOS.</h1>
        </div>

        <div class="cardsCoaches">
            <?php
            $query = "SELECT * FROM Productos";
            $result = $conn->query($query);
            while ($producto = $result->fetch_assoc()) {
                echo '<div class="card">
            <div class="face front">
                <img src="' . htmlspecialchars($producto['Foto']) . '" alt="' . htmlspecialchars($producto['Nombre']) . '">
                <h3>' . htmlspecialchars($producto['Nombre']) . '</h3>
            </div>
            <div class="face back">
                <div class="back-img" style="background-image: url(' . htmlspecialchars($producto['Foto']) . ');"></div>
                <h3>' . htmlspecialchars($producto['Nombre']) . '</h3>
                <p>' . htmlspecialchars($producto['DescripcionCorta']) . '</p>
                <div class="link">
                    <a href="producto1BBDD.php?id=' . htmlspecialchars($producto['ID']) . '">Saber más</a>
                </div>
            </div>
        </div>';
            }
            ?>
        </div>

    </main>

    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="./scripts/carruselProducto.js"></script>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php include('footer.php'); ?>
</body>

</html>