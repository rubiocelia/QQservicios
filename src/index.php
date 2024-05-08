<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="index">

    <?php
    // Inicia o continua una sesión existente
    session_start();

    // Verifica si la sesión está iniciada y si $id_usuario está definido
    if (isset($_SESSION['id_usuario'])) {
        include('menu_sesion_iniciada.php');
    } else {
        include('menu.php');
    }
    ?>
    <main>
        <div class="fondo">
            <div class="parrfInicial">
                <h1 class="titulo">NUESTROS SERVICIOS</h1>
                <p class="txtInicial">
                    ¡Explora nuevas habilidades con nuestros cursos introductorios!
                    ¿Listo para descubrir un mundo de posibilidades? Nuestros cursos te brindan el punto de partida
                    perfecto
                    para adquirir nuevas habilidades y abrirte a nuevas oportunidades. ¡Inscríbete hoy y da el primer
                    paso
                    hacia
                    el éxito!
                </p>
                <?php

                // Verifica si la variable de sesión 'id_usuario' no está definida
                if (!isset($_SESSION['id_usuario'])) {
                    // Muestra el botón si el usuario no ha iniciado sesión
                    echo '<a class="btnUnete" id="btnUnete">¡Únete!</a>';
                }
                ?>
            </div>
        </div>
        <div class="info">
            <h1>Descubre cómo podemos ayudarte a crecer</h1>
            <div class="fila">
                <div class="cuad1">
                    <h4>Una <span class="subRojo">Plataforma Única</span> para un Aprendizaje Personalizado</h4>
                </div>
                <div class="cuad2">
                    <img class="icono" src="../src/archivos/index/ajustes.png" alt="linkedin">
                </div>
                <div class="cuad3">
                    <p>Nuestra plataforma ofrece una experiencia de aprendizaje personalizada y adaptativa en coaching,
                        ajustándose a las necesidades y ritmos únicos de cada usuario.</p>
                </div>
            </div>

            <div class="fila">
                <div class="cuad3">
                    <p>Nuestro objetivo es ofrecer un espacio que permita a los participantes adquirir habilidades
                        prácticas de liderazgo y vida personal, brindando los recursos y el apoyo necesarios para
                        alcanzar sus metas personales y profesionales.</p>
                </div>
                <div class="cuad2">
                    <img class="icono" src="../src/archivos/index/auto-crecimiento.png" alt="linkedin">
                </div>
                <div class="cuad1">
                    <h4>Nuestro Compromiso: Tu <span class="subRojo">Crecimiento y Éxito Personal</span></h4>

                </div>
            </div>

            <div class="fila">
                <div class="cuad1">
                    <h4>¿Qué <span class="subRojo">valor</span> me puede añadir?</h4>
                </div>
                <div class="cuad2">
                    <img class="icono" src="../src/archivos/index/valor.png" alt="linkedin">
                </div>
                <div class="cuad3">
                    <p>Nuestros programas de coaching mejoran la toma de decisiones, clarifican objetivos, desarrollan
                        habilidades comunicativas y expanden tu red profesional, preparándote para avanzar en tu
                        carrera.</p>
                </div>
            </div>
        </div>

        <div>
            <img class="fotoInicio" src="../src/archivos/index/coaching.jpg" alt="fotoInicio">

        </div>
        <h1 class="testimonios">Testimonios</h1>
        <div class="testimonial-slider">
            <div class="testimonial-item active">
                <img class="fotoTestimonio" src="../src/archivos/index/coaching.jpg" alt="fotoInicio">
                <h4>Juan Pérez</h4>
                <p>Este es un testimonio fantástico. ¡El servicio fue excelente y el producto es de alta calidad!</p>

            </div>
            <div class="testimonial-item">
                <img class="fotoTestimonio" src="../src/archivos/index/fondo.png" alt="fotoInicio">
                <h4>Maria López</h4>
                <p>Muy satisfecho con la compra. Llegó a tiempo y cumple con todas las expectativas.</p>

            </div>
            <div class="testimonial-item">
                <img class="fotoTestimonio" src="../src/archivos/index/coaching.jpg" alt="fotoInicio">
                <h4>Carlos Jiménez</h4>
                <p>Increíble experiencia de principio a fin. Recomiendo ampliamente este servicio.</p>

            </div>
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>




    </main>



    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/testimonios.js"></script>
    <?php include('footer.php'); ?>

</body>

</html>