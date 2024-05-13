<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <!-- Enlace a la hoja de estilos para los coaches -->
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/coaches.css">
    <!-- Icono de la pestaña del navegador -->
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- Enlace a la librería de animaciones animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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
        <!-- Sección del fondo con título y descripción -->
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

        <!-- Sección con información sobre los coaches -->
        <div class="info">
            <h1>Creemos firmemente que la calidad del aprendizaje depende de la excelencia de 
                los instructores. Nuestro equipo de coaches altamente calificados está dedicado a 
                ayudarte a alcanzar tus metas personales y profesionales.</h1>
        </div>

        <!-- Carrusel de testimonios -->
        <div class="testimonial-slider">
            <?php
            // Consulta a la base de datos para obtener los coaches
            $query = "SELECT * FROM Coaches";
            $result = $conn->query($query);
            $first = true;
            // Iteración sobre los resultados de la consulta
            while ($coach = $result->fetch_assoc()) {
                // Impresión de cada testimonio
                echo '
            
            <div class="testimonial-item ' . ($first ? 'active' : '') . '">
            <div class="testimonial-carrusel " class="animated-element">
            <img class="fotoTestimonio"  src="' . $coach['Foto'] . '" alt="' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '">
            <div class="testimonial-text">
            <h4>' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '</h4>
            <p>' . $coach['Experiencia'] . '</p>
            <div class="testimonial-icons">
            <img src="./archivos/linkedin_cuadrado.png" alt="botón Linkedin">
            <img src="./archivos/youtube.png" alt="botón multimedia">
            <img src="./archivos/descripcion-general.png" class="icon-general" alt="">
            </div>
          </div>
          </div>
          </div>';
                $first = false;
            }
            ?>
            <!-- Botones de navegación del carrusel -->
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>

    </main>

    <!-- Enlaces a los scripts JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="./scripts/coachesCarrusel.js"></script>
    <!-- Script para animaciones al hacer scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeIn');
                    } else {
                        entry.target.classList.remove('animate__animated', 'animate__fadeIn');
                    }
                });
            });

            const elements = document.querySelectorAll('.animated-element');
            elements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>

    <!-- Inclusión del pie de página -->
    <?php include('footer.php'); ?>
</body>

</html>
