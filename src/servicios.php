<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/Servicios.css">
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

        <div class="video-background">
            <video autoplay muted loop class="video">
                <source src="../../archivos/videoEjemplo1.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>
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

        <!-- MENÚ LATERAL DE FILTROS -->
        <!-- 
        <button id="openBtn" onclick="toggleSidebar()">☰</button>

        <div id="sidebar" class="sidebar">
            <h2>Filtros</h2>
            <div class="dropdown">
                <a href="#">Categoría</a>
                <div class="dropdown-content">
                    <label><input type="checkbox" name="categoria" value="categoria1"> Categoría 1</label><br>
                    <label><input type="checkbox" name="categoria" value="categoria2"> Categoría 2</label><br>
                    <label><input type="checkbox" name="categoria" value="categoria3"> Categoría 3</label><br>
                     Agrega más categorías si es necesario
                </div>
            </div>
            <div class="dropdown">
                <a href="#">Precio</a>
                <div class="dropdown-content">
                    <label><input type="checkbox" name="precio" value="precio1"> Precio 1</label><br>
                    <label><input type="checkbox" name="precio" value="precio2"> Precio 2</label><br>
                    <label><input type="checkbox" name="precio" value="precio3"> Precio 3</label><br>
                     Agrega más opciones de precios si es necesario
                </div>
            </div>
            Agrega más filtros desplegables según tus necesidades
        </div>

        <script>
            function toggleSidebar() {
                var sidebar = document.getElementById("sidebar");
                if (sidebar.style.width === "250px") {
                    sidebar.style.width = "0";
                } else {
                    sidebar.style.width = "250px";
                }
            }
        </script>
        -->
    </main>

    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="../src/scripts/carruselProducto.js"></script>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="../src/scripts/validacionRegistro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php include('footer.php'); ?>
</body>

</html>