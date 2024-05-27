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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <h1 class="titulo">Metodología QQ Experiencial e Inmersiva</h1>
                <p class="txtInicial">
                    En QQ Experiences, experimentarás una metodología única que combina reflexión y práctica en
                    ambientes especialmente diseñados para ti. Este enfoque te permitirá crecer tanto profesional como
                    personalmente, asegurando un aprendizaje profundo y significativo.
                </p>
            </div>
        </div>

        <!-- Sección con información sobre los coaches -->
        <div class="info">
            <p>En cualquier programa de desarrollo profesional el contenido es
                importante, pero la forma y el contexto son aspectos clave a la hora
                de entender y anclar cualquier concepto. Por ello, nuestra
                metodología propia tiene nombre: <strong>QQ Experiences</strong> , que da identidad
                y valor a todos nuestros programas de desarrollo individual y
                colaborativo. Es una metodología experiencial, basada en los detalles,
                que deja huella. En muchos casos utilizamos dos ambientes
                claramente diferenciados: el área de reflexión y puesta en común; y
                un espacio experiencial que será el “laboratorio” perfecto a la hora de
                vivir estas actividades. (Espacios distintos y específicos según el tipo
                de Experiencia).</p>
        </div>


        <div class="container">
            <h2>Gracias a QQ Experiences, los resultados son siempre más satisfactorios:</h2>
            <div class="programas">
                <img src="./archivos/coaches/1.png">
                <img src="./archivos/coaches/3.png">
                <img src="./archivos/coaches/2.png">
                <img src="./archivos/coaches/4.png">
            </div>
        </div>

        <div class="container2">
            <h2>Gráfico de Aprendizaje</h2>
            <canvas id="myChart"></canvas>
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
            <p>' . $coach['Descripcion'] . '</p>
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