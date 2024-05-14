<?php
    // Iniciar sesión
    session_start();

    // Verificar si el ID de usuario está almacenado en la sesión
    if (!isset($_SESSION['id_usuario'])) {
        // Si el ID de usuario no está almacenado en la sesión, redirigir al usuario al formulario de inicio de sesión
        header("Location: index.php");
        exit();
    }

    // El ID de usuario está definido en la sesión
    $idUsuario = $_SESSION['id_usuario'];
    
    // Obtener los datos del usuario
    require_once("./bbdd/conecta.php");
    $conexion = getConexion();
    $sql = "SELECT * FROM Usuarios WHERE ID = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idUsuario); // 'i' para indicar que es un entero (ID)
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        // No se encontraron resultados, posible manejo de error o redirección
        echo "No se encontró información para el usuario con el ID proporcionado.";
        $conexion->close();
        exit();
    }

    // Obtener los datos del usuario
    $usuario = $resultado->fetch_assoc();
    $conexion->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>producto1</title>
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">

    <link rel="stylesheet" href="../src/estilos/css/producto2.css">
</head>

<body class="fondoProducto">
    <header>
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
    </header>
    <main>
        <div class="titulo">
            <h1>Desarrolla tus Competencias como líder. Neuroliderazgo</h1>
            <div class="descripcion">
                <p>Consigue un desempeño óptimo de tus funciones y la correcta gestión emocional que mejore tu
                    equilibrio y balance personal. Programa Completo y Práctico para potenciar las competencias
                    del
                    liderazgo eficaz requerido en este Siglo21.Impulsa tu Creatividad, fomenta entornos de
                    Colaboración y Gestiona la Diversidad. Duración: 6 sesiones de 1,5h. </p>
            </div>
            <div class="botonesPrecioComprar">
                <button class="btnComprar">Comprar</button>
                <p>100€</p>

            </div>
        </div>
        <div class="InformProd">
            <!-- carrusel multimedia -->
            <div class="carrFotos">
                <div class="fotosVideos">
                    <img src="../src/archivos/productos/carruselProducto1/Julia y Javier Conversacion.JPEG"
                        class="carrusel-item" alt="">
                    <img src="../src/archivos/productos/carruselProducto1/javier_ontiveros.jpeg"
                        class="carrusel-item visible" alt="">
                    <video src="../src/archivos/productos/carruselProducto1/video1.mp4" class="carrusel-item"
                        controls></video>
                </div>

                <!-- Botones de navegación -->
                <button class="btnNav prev">&#10094;</button>
                <button class="btnNav next">&#10095;</button>

            </div>

            <!-- contenidos del producto -->

            <div class="contenidoProd">
                <h1 class="TitContenidos">Contenidos</h1>
                <!-- Contenidos desplegables -->
                <div class="contenidosTXT">
                    <div class="tituloCont">Sesión Previa Programa Coaching (1,5 horas)</div>
                    <div class="respuestaCont">
                        <ul>
                            <li>Clarificando qué es y no es coaching</li>
                            <li>Pulsar el momento del líder/coachee. Compartir objetivos de cambio</li>
                            <li>Plantear el camino de evolución para la búsqueda de autonomía, despliegue de
                                talento y desarrollo directivo</li>
                        </ul>
                    </div>
                </div>

                <div class="contenidosTXT">
                    <div class="tituloCont">Situación Actual Líder. Evaluación 360º

                    </div>
                    <div class="respuestaCont">
                        <ul>
                            <li>Evaluación 360º competencias modelo i4* de neuroliderazgo (evaluación
                                individual
                                + evaluación de 10 personas).</li>
                            <li>Comprensión global personal del presente</li>
                            <li>Definir imagen clara del rol/ situación profesional actual</li>
                            <li>Identificar necesidades de cambio</li>
                            <li>Identificar áreas de desarrollo y fortalezas</li>
                        </ul>
                    </div>
                </div>

                <div class="contenidosTXT">
                    <div class="tituloCont">Toma de Conciencia y Definición del Cambio</div>
                    <div class="respuestaCont">
                        <ul>
                            <li>Report Individual-Confidencial de los Resultados de competencias en el
                                modelo
                                i4* neurolíder</li>
                            <li>Visualización de niveles de competencias (propias y externas)</li>
                            <li>Fortalezas y Debilidades Compartidas. Puntos Ciegos</li>
                            <li>Definir la visión deseada, metas camino y objetivos</li>
                            <li>Generar y seleccionar perspectivas generadoras de valor</li>
                            <li>Elaboración detallada de un plan de mejora individual (PMI)
                            </li>
                            <li>Generación de nuevos compromisos individuales de cambio</li>
                        </ul>
                    </div>
                </div>

                <div class="contenidosTXT">
                    <div class="tituloCont">Proceso de Desarrollo Individual

                    </div>
                    <div class="respuestaCont">
                        <ul>
                            <li>Seguimiento del plan de acción y consolidar cambios</li>
                            <li>Reevaluar realidades y decisiones</li>
                            <li>Herramientas requeridas por la persona para el cambio</li>
                            <li>Identificar avances, barreras, y ayudas necesarias</li>
                        </ul>
                    </div>
                </div>

                <div class="contenidosTXT">
                    <div class="tituloCont">Sesión Final de Evaluación de Impacto

                    </div>
                    <div class="respuestaCont">
                        <ul>
                            <li>Coach y Coachee evalúan el impacto y resultados del Programa de Coaching
                            </li>
                            <li>Situación actual del entorno profesional y el alcance de los objetivos
                                definidos
                            </li>
                            <li>Planteamiento futuro después del Coaching</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- testimonios -->

            <div class="testimonios">
                <h1 class="titTestimonios">Testimonios</h1>
                <!-- Carrusel de testimonios -->
                <div class="testimonial">
                    <img src="../src/archivos/productos/foto1.jpg" class="fotoTestimonio" alt="">
                    <h2>Mª Rosa León Mateo</h2>
                    <h4>Socia Fundadora Network Courier</h4>
                    <p>Mi querido Coach, como tantas veces te he dicho mi proceso como cochee contigo fue de
                        las
                        mejores cosas que he hecho en mi carrera profesional. Lo único que siento es no
                        haberlo
                        hecho antes... Si la forma de ejercer el liderazgo es siempre la base para obtener
                        los
                        mejores resultados, tener al equipo motivado, en definitiva tener el mejor retorno
                        de
                        nuestras acciones profesionales, en el periodo que estamos viviendo es fundamental.
                        Mi
                        proceso de aprendizaje como tu cochee sigue vivo y presente en mi, todos los días y
                        procuro ejercerlo a diario, sacando mi mejor versión en este periodo tan complicado
                        que
                        estamos viviendo, con la satisfacción que eso supone.

                    </p>
                </div>

                <div class="testimonial">
                    <img src="../src/archivos/productos/foto1.jpg" class="fotoTestimonio" alt="">
                    <h2>Beatriz Achaques</h2>
                    <h4>CEO & Founder Dubita Arts&People</h4>
                    <p>Es una bellísima persona y un profesional HUMANO. Tiene un don, que es ayudar a los
                        demás
                        y una sabiduría infinita. Es una de esas personas a las que acudir en momentos
                        claves de
                        tu vida. Sabe escuchar, leer a las personas y sembrar la semilla de crecimiento en
                        el
                        coachee para que una vez acabado el proceso sea uno mismo el que con las
                        herramientas
                        conseguidas en el proceso, pueda hacerla crecer de forma independiente. Me ha
                        ayudado a
                        alzar el vuelo. Solo tengo palabras de agradecimiento y gratitud hacia Javier.
                    </p>
                </div>

                <div class="testimonial">
                    <img src="../src/archivos/productos/foto1.jpg" class="fotoTestimonio" alt="">
                    <h2>Ramón Fco. Pérez Ruiz</h2>
                    <h4>Senior National Manager GLS</h4>
                    <p>Buenas tardes Javier, para nuestro desarrollo profesional fuiste una influencia muy
                        positiva gracias 👍
                    </p>
                </div>

                <button class="prevTest">&#10094;</button>
                <button class="nextTest">&#10095;</button>
            </div>
        </div>
    </main>

    <script src="../src/scripts/carruselProducto2.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>