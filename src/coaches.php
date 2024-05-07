<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/coaches.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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

        <div class="testimonial-slider">
            <?php
            $query = "SELECT * FROM Coaches";
            $result = $conn->query($query);
            $first = true;
            while ($coach = $result->fetch_assoc()) {


                echo '
            
            <div class="testimonial-item ' . ($first ? 'active' : '') . '">
            <div class="testimonial-carrusel " class="animated-element">
            <img class="fotoTestimonio"  src="' . $coach['Foto'] . '" alt="' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '">
            <div class="testimonial-text">
            <h4>' . $coach['Nombre'] . ' ' . $coach['Apellidos'] . '</h4>
            <p>' . $coach['Experiencia'] . '</p>
            <div class="testimonial-icons">
            <img src="./archivos/linkedin_cuadrado.png" alt="botón Linkedin">
              <img src="./archivos/boton-de-play.png" alt="botón multimedia">
            </div>
          </div>
          </div>
          </div>';
                $first = false;
            }
            ?>
            <!-- Aquí van los botones de navegación -->
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>



    </main>

    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="./scripts/coachesCarrusel.js"></script>
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


    <?php include('footer.php'); ?>
</body>

</html>