<?php
// Iniciar sesión
session_start();

// Conectar a la base de datos
require_once("./bbdd/conecta.php");
$conexion = getConexion();

// Obtener todos los testimonios
$testimonioQuery = "SELECT Nombre, Subtitulo, Descripcion, Foto FROM Testimonios";
$testimonioResult = $conexion->query($testimonioQuery);

// Obtener 4 productos aleatorios
$productoQuery = "SELECT ID, Nombre, DescripcionCorta, Foto FROM Productos ORDER BY RAND() LIMIT 5";
$productoResult = $conexion->query($productoQuery);

// Cerrar la conexión
$conexion->close();
?>





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
    if (session_status() == PHP_SESSION_NONE) {
        // Si no hay sesión activa, iniciar una nueva sesión
        session_start();
    }

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
                <h1 class="titulo">Transforma Tu Negocio con Nuestros Servicios Profesionales</h1>
                <h2 class="subtitulo">Soluciones personalizadas para llevar tu empresa al siguiente nivel</h2>
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

        <!-- Carrusel de Productos -->
        <div class="carousel-container">
            <h1>Productos Destacados</h1>
            <div class="carousel">
                <?php
                if ($productoResult->num_rows > 0) {
                    while ($producto = $productoResult->fetch_assoc()) {
                        echo "<div class='carousel-item'>";
                        echo "<img src='" . htmlspecialchars($producto['Foto']) . "' alt='" . htmlspecialchars($producto['Nombre']) . "'>";
                        echo "<h2>" . htmlspecialchars($producto['Nombre']) . "</h2>";
                        echo "<p>" . htmlspecialchars($producto['DescripcionCorta']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No se encontraron productos.</p>";
                }
                ?>
            </div>
        </div>
        <div class="beneficios">
            <h1>¿Por qué elegirnos?</h1>
            <div class="benefCuad">
                <div class="beneficio">
                    <img src="../src/archivos/index/medalla.png" class="logoBenef">
                    <p>Expertos en la Industria</p>
                </div>
                <div class="beneficio">
                    <img src="../src/archivos/index/personalizar.png" class="logoBenef">
                    <p>Soluciones Personalizadas</p>
                </div>
                <div class="beneficio">
                    <img src="../src/archivos/index/garantizar.png" class="logoBenef">
                    <p>Resultados Garantizados</p>
                </div>
            </div>
        </div>

        <div class="faldon">
            <div class="video-container">
                <iframe class="videoFaldon" src="https://www.youtube.com/embed/N_H54I-DSJY?si=uNiSUsRykz4Ksm1X"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="texto-container">
                <h2>Vive la Innovación en el Evento QQ Clients</h2>
                <p>
                    Sumérgete en una experiencia transformadora con nuestro video resumen del Evento QQ Clients.
                    Este
                    evento de co-creación se centra en revolucionar la Experiencia de Cliente a través de la
                    Innovación,
                    Creatividad, y Neuroliderazgo. Descubre cómo los valores y la cultura pueden impulsar la
                    Transformación Digital en tu organización.
                </p>
                <br>
                <p>
                    ¡No te pierdas los momentos más inspiradores y las ideas más impactantes de líderes y expertos
                    en la
                    industria! Haz clic en el video para ser parte de esta jornada única de aprendizaje y
                    crecimiento.
                </p>
            </div>
        </div>

        <div class="datos-interactivos">
            <h2>Lo que hemos logrado</h2>
            <div class="infografia">

                <div class="dato">
                    <h3 class="count" data-target="10800">0</h3>
                    <p>Asistentes satisfechos</p>
                </div>
                <div class="dato">
                    <h3 class="count" data-target="15">0</h3>
                    <p>Años de experiencia</p>
                </div>
                <div class="dato">
                    <h3 class="count" data-target="25">0</h3>
                    <p>Empresas</p>
                </div>
            </div>
        </div>

        <div class="cta-secundaria">
            <h2>¿Listo para Empezar?</h2>
            <p>Contáctanos hoy y descubre cómo podemos ayudarte a alcanzar tus objetivos.</p>
            <a href="contacto.php" class="cta-button">Contacta con nosotros</a>
        </div>

        <div class="testimonios">
            <h1>Lo que dicen nuestros clientes</h1>
            <div class="testimonial-slider">
                <div id="testimonios" class="testimonios">
                    <!-- Carrusel de testimonios -->
                    <?php
                        if ($testimonioResult->num_rows > 0) {
                            // Convertir el resultado en un array
                            $testimonios = [];
                            while ($testimonio = $testimonioResult->fetch_assoc()) {
                                $testimonios[] = $testimonio;
                            }
                            // Mezclar el array para mostrar los testimonios en orden aleatorio
                            shuffle($testimonios);
                            // Mostrar los testimonios
                            foreach ($testimonios as $testimonio) {
                                echo "<div class='testimonial'>";
                                echo "<div class='testimonial-content'>";
                                echo "<img class='fotoTestimonio' src='" . htmlspecialchars($testimonio["Foto"]) . "' alt='" . htmlspecialchars($testimonio["Nombre"]) . "'>";
                                echo "<h2>" . htmlspecialchars($testimonio['Nombre']) . "</h2>";
                                echo "<h4>" . htmlspecialchars($testimonio['Subtitulo']) . "</h4>";
                                echo "<p>" . htmlspecialchars($testimonio['Descripcion']) . "</p>";
                                echo "</div>"; // Close testimonial-content div
                                echo "</div>"; // Close testimonial div
                            }
                        } else {
                            echo "<p>No se encontraron testimonios para este producto.</p>";
                        }
                    ?>
                    <button class="prevTest">&#10094;</button>
                    <button class="nextTest">&#10095;</button>
                </div>
            </div>
        </div>




    </main>



    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/validacionRegistro.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./scripts/index.js"></script>
    <?php include('footer.php'); ?>

</body>

</html>