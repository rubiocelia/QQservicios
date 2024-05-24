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

// Obtener los coaches relacionados con el producto
$coachesQuery = "SELECT C.* FROM Coaches C INNER JOIN Productos P ON C.ID = P.ID_coaches WHERE P.ID = ?";
$coachesStmt = $conexion->prepare($coachesQuery);
$coachesStmt->bind_param("i", $idProducto);
$coachesStmt->execute();
$coachesResult = $coachesStmt->get_result();

// Obtener los datos del carrusel de multimedia relacionados con el producto
$carruselQuery = "SELECT RutaArchivos FROM carruselMultimedia WHERE ID_Producto = ?";
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['Nombre']); ?></title>
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <link rel="stylesheet" href="../src/estilos/css/producto1.css">
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
                <p><?php echo htmlspecialchars($producto['Precio']); ?>€</p>
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
                                        $extension = pathinfo($carrusel['RutaArchivos'], PATHINFO_EXTENSION);
                                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                            echo "<img src='". htmlspecialchars($carrusel['RutaArchivos']) ."' class='carrusel-item' alt=''>";
                                        } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                                            echo "<video src='". htmlspecialchars($carrusel['RutaArchivos']) ."' class='carrusel-item' controls></video>";
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
                                <p>👣<?php echo htmlspecialchars($producto['txtLibre']); ?></p>
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
</body>

</html>