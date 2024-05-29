<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está registrado
$idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Obtener los datos del usuario
require_once("./bbdd/conecta.php");
$conexion = getConexion();

// Obtener el ID del producto desde la URL
$idProducto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idProducto == 0) {
    // Si el ID del producto no es válido, manejar el error o redirigir
    echo "ID de producto no válido.";
    $conexion->close();
    exit();
}

// Obtener la información del producto con el ID proporcionado
$productoQuery = "SELECT * FROM Productos WHERE ID = ?";
$productoStmt = $conexion->prepare($productoQuery);
$productoStmt->bind_param("i", $idProducto);
$productoStmt->execute();
$productoResult = $productoStmt->get_result();

if ($productoResult->num_rows == 0) {
    // No se encontraron resultados, posible manejo de error o rediricción
    echo "No se encontró información para el producto con el ID proporcionado.";
    $conexion->close();
    exit();
}

// Obtener los datos del producto
$producto = $productoResult->fetch_assoc();

// Obtener los contenidos relacionados con el producto
$contenidoQuery = "SELECT Titulo, Descripcion FROM Contenidos WHERE ID_Producto = ?";
$contenidoStmt = $conexion->prepare($contenidoQuery);
$contenidoStmt->bind_param("i", $idProducto);
$contenidoStmt->execute();
$contenidoResult = $contenidoStmt->get_result();

// Obtener los testimonios relacionados con el producto
$testimonioQuery = "SELECT Nombre, Subtitulo, Descripcion, Foto FROM Testimonios WHERE ID_Producto = ?";
$testimonioStmt = $conexion->prepare($testimonioQuery);
$testimonioStmt->bind_param("i", $idProducto);
$testimonioStmt->execute();
$testimonioResult = $testimonioStmt->get_result();

// Obtener los coaches relacionados con el producto usando la tabla de relación
$coachesQuery = "SELECT C.* FROM Coaches C INNER JOIN ProductoCoaches PC ON C.ID = PC.ID_Coach WHERE PC.ID_Producto = ?";
$coachesStmt = $conexion->prepare($coachesQuery);
$coachesStmt->bind_param("i", $idProducto);
$coachesStmt->execute();
$coachesResult = $coachesStmt->get_result();

// Obtener los datos del carrusel de multimedia relacionados con el producto, ordenados por la columna Orden
$carruselQuery = "SELECT cm.Tipo, cm.URL_Local, cm.URL_Youtube 
                  FROM GaleriaContenido gc 
                  INNER JOIN ContenidoMultimedia cm 
                  ON gc.ID_Contenido = cm.ID 
                  WHERE gc.ID_Galeria = (SELECT Id_galeria FROM Productos WHERE ID = ?)
                  ORDER BY gc.Orden";
$carruselStmt = $conexion->prepare($carruselQuery);
$carruselStmt->bind_param("i", $idProducto);
$carruselStmt->execute();
$carruselResult = $carruselStmt->get_result();

// Obtener las FAQs relacionadas con el producto
$faqQuery = "SELECT Pregunta, Respuesta FROM faqs WHERE ID_Producto = ?";
$faqStmt = $conexion->prepare($faqQuery);
$faqStmt->bind_param("i", $idProducto);
$faqStmt->execute();
$faqResult = $faqStmt->get_result();

$conexion->close();

function convertirUrlYoutube($url) {
    $parsedUrl = parse_url($url);
    $videoId = '';
    $startTime = '';

    if (strpos($parsedUrl['host'], 'youtube.com') !== false) {
        parse_str($parsedUrl['query'], $queryParams);
        $videoId = $queryParams['v'];
        if (isset($queryParams['t'])) {
            $startTime = convertirTiempoASegundos($queryParams['t']);
        }
    } elseif (strpos($parsedUrl['host'], 'youtu.be') !== false) {
        $videoId = ltrim($parsedUrl['path'], '/');
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['t'])) {
                $startTime = convertirTiempoASegundos($queryParams['t']);
            }
        }
    }

    return 'https://www.youtube.com/embed/' . $videoId . '?start=' . $startTime;
}

function convertirTiempoASegundos($tiempo) {
    if (is_numeric($tiempo)) {
        return $tiempo;
    }

    $tiempo = strtolower($tiempo);
    $segundos = 0;
    preg_match_all('/(\d+)([hms])/', $tiempo, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        list(, $cantidad, $unidad) = $match;
        switch ($unidad) {
            case 'h':
                $segundos += $cantidad * 3600;
                break;
            case 'm':
                $segundos += $cantidad * 60;
                break;
            case 's':
                $segundos += $cantidad;
                break;
        }
    }

    return $segundos;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['Nombre']); ?></title>
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <link rel="stylesheet" href="../src/estilos/css/producto1.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js"></script>

    <style>
    .fondoProducto {
        background-image: url('<?php echo htmlspecialchars($producto['FotoFondo']); ?>'), url('./archivos/productos/BackgroundCubes_V2.svg');
    }
    </style>
</head>

<body class="fondoProducto">
    <header>
        <?php
        if ($idUsuario) {
            include('menu_sesion_iniciada.php');
        } else {
            include('menu.php');
        }
        ?>
    </header>
    <main>
        <div class="titulo">
            <h1><?php echo htmlspecialchars($producto['Nombre']); ?></h1>
            <div class="descripcion">
                <p><?php echo ($producto['Descripcion']); ?></p>
            </div>
            <div class="botonesPrecioComprar">
                <?php if ($idUsuario): ?>
                <p><?php echo htmlspecialchars($producto['Precio']); ?></p>
                <?php endif; ?>
                <button class="btnComprar" <?php if (!$idUsuario) echo 'id="comprarBtn"' ?>>Comprar</button>
            </div>
        </div>
        <div class="todo">
            <div class="contenidos">
                <div class="cajaIzquierda">
                    <div class="menuHorizontal">
                        <div class="menuContTest">
                            <button id="contenidosBtn" class="tab active">Contenidos</button>
                            <button id="testimoniosBtn" class="tab">Testimonios</button>
                        </div>
                        <div id="contenidos" class="contenido active">
                            <!-- Contenidos desplegables -->
                            <?php
                            if ($contenidoResult->num_rows > 0) {
                                while ($contenido = $contenidoResult->fetch_assoc()) {
                                    echo "<div class='contenidosTXT'>";
                                    echo "<div class='tituloCont'>" . htmlspecialchars($contenido['Titulo']) . "</div>";
                                    echo "<div class='respuestaCont'>" . $contenido['Descripcion'] . "</div>"; // No se usa htmlspecialchars aquí para permitir HTML
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No se encontraron contenidos para este producto.</p>";
                            }
                            ?>
                        </div>
                        <div id="testimonios" class="testimonios">
                            <!-- Carrusel de testimonios -->
                            <?php
                            if ($testimonioResult->num_rows > 0) {
                                while ($testimonio = $testimonioResult->fetch_assoc()) {
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
                        <div class="carrFotos">
                            <div class="fotosVideos">
                                <?php
                                if ($carruselResult->num_rows > 0) {
                                    while ($carrusel = $carruselResult->fetch_assoc()) {
                                        if ($carrusel['Tipo'] == 'foto') {
                                            echo "<img src='". htmlspecialchars($carrusel['URL_Local']) ."' class='carrusel-item' alt=''>";
                                        } elseif ($carrusel['Tipo'] == 'video_local') {
                                            echo "<video src='". htmlspecialchars($carrusel['URL_Local']) ."' class='carrusel-item' controls></video>";
                                        } elseif ($carrusel['Tipo'] == 'video_youtube') {
                                            $embedUrl = convertirUrlYoutube($carrusel['URL_Youtube']);
                                            echo "<iframe src='". htmlspecialchars($embedUrl) ."' class='carrusel-item' width='480' height='360' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                                        }
                                    }
                                } else {
                                    echo "<p>No se encontraron archivos multimedia para este producto.</p>";
                                }
                                ?>
                            </div>
                            <!-- Botones de navegación -->
                            <button class="btnNav prev">&#10094;</button>
                            <button class="btnNav next">&#10095;</button>
                        </div>
                    </div>
                </div>
                <div class="cajaDerecha">
                    <div class="contenedor-cajas">
                        <div id="carruselCoaches" class="carrusel">
                            <!-- Caja de un coach -->
                            <?php
                            if ($coachesResult->num_rows > 0) {
                                while ($coach = $coachesResult->fetch_assoc()) {
                                    echo "<div class='coach'>";
                                    echo "<img src='" . htmlspecialchars($coach["Foto"]) . "' alt='" . htmlspecialchars($coach["Nombre"]) . "'>";
                                    echo "<h2>" . htmlspecialchars($coach['Nombre']) . " " . htmlspecialchars($coach['Apellidos']) ."</h2>";
                                    echo "<h3>" . htmlspecialchars($coach['Titulacion']) . "</h3>";
                                    echo "<p>" . htmlspecialchars($coach['Descripcion']) . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>No se encontraron coaches.</p>";
                            }
                            ?>
                            <button class="prevCoaches">&#10094;</button>
                            <button class="nextCoaches">&#10095;</button>
                        </div>

                        <div class="cajaComprar">
                            <h1>¡Potencia tu futuro!</h1>

                            <?php if (!empty($producto['Duracion']) || !empty($producto['Modalidad']) || !empty($producto['txtLibre'])): ?>
                            <div class="infoProduct">
                                <?php if (!empty($producto['Duracion'])): ?>
                                <p>⏱️ Duración: <?php echo htmlspecialchars($producto['Duracion']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($producto['Modalidad'])): ?>
                                <p>👥 Modalidad: <?php echo htmlspecialchars($producto['Modalidad']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($producto['txtLibre'])): ?>
                                <p>🎯<?php echo htmlspecialchars($producto['txtLibre']); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="image-container">
                                <img src="../src/archivos/productos/Trofeo.png" id="staticImage" alt="Imagen estática">
                                <img src="../src/archivos/productos/trophy.gif" id="animatedImage" class="hidden"
                                    alt="GIF animado">
                            </div>
                            <button class="btnComprar2"
                                <?php if (!$idUsuario) echo 'id="comprarBtn"' ?>>Comprar</button>

                        </div>
                    </div>

                </div>

            </div>
            <!-- Sección de FAQs -->
            <div class="faqSection">
                <h2>Preguntas Frecuentes</h2>
                <?php
                    if ($faqResult->num_rows > 0) {
                        while ($faq = $faqResult->fetch_assoc()) {
                            echo "<div class='faq'>";
                            echo "<h3>" . htmlspecialchars($faq['Pregunta']) . "</h3>";
                            echo "<p>" . htmlspecialchars($faq['Respuesta']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No se encontraron FAQs para este producto.</p>";
                    }
                    ?>
            </div>
        </div>
    </main>
    <script src="../src/scripts/carruselProducto1.js"></script>
    <script src="./scripts/scriptPopUp.js"></script>




    <?php include('footer.php'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let controller = new ScrollMagic.Controller();

        let scene = new ScrollMagic.Scene({
                triggerElement: 'body', // Elemento que desencadena la animación
                triggerHook: 'onLeave', // Comienza cuando el elemento sale de la vista
                offset: 1 // Un pequeño desplazamiento para evitar activación inmediata
            })
            .on('start', function(event) {
                if (event.scrollDirection === 'FORWARD') {
                    window.scrollTo({
                        top: window.innerHeight * 1.2,
                        behavior: 'smooth'
                    });
                }
            })
            .addTo(controller);

        // Resetear el estado cuando el usuario vuelve a la parte superior
        window.addEventListener('scroll', function() {
            if (window.scrollY === 0) {
                controller.update(true);
            }
        });
    });
    </script>

</body>

</html>