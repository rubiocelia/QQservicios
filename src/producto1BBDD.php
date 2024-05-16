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
    // No se encontraron resultados, posible manejo de error o rediricción
    echo "No se encontró información para el usuario con el ID proporcionado.";
    $conexion->close();
    exit();
}

// Obtener los datos del usuario
$usuario = $resultado->fetch_assoc();

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
        if (isset($_SESSION['id_usuario'])) {
            include('menu_sesion_iniciada.php');
        } else {
            include('menu.php');
        }
        ?>
    </header>
    <main>
        <div class="titulo">
            <h1><?php echo htmlspecialchars($producto['Nombre']); ?></h1>
        </div>
        <div class="contenidos">
            <div class="cajaIzquierda">
                <div class="descripcion">
                    <p><?php echo htmlspecialchars($producto['Descripcion']); ?></p>
                </div>
                <div class="botonesPrecioComprar">
                    <p><?php echo htmlspecialchars($producto['Precio']); ?>€</p>
                    <button class="btnComprar">Comprar</button>
                </div>
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
                <div class="menuHorizontal">
                    <div class="menuContTest">
                        <button id="contenidosBtn">Contenidos</button>
                        <button id="testimoniosBtn">Testimonios</button>
                    </div>
                    <div id="contenidos" class="contenido">
                        <!-- Contenidos desplegables -->
                        <?php
                        if ($contenidoResult->num_rows > 0) {
                            while ($contenido = $contenidoResult->fetch_assoc()) {
                                echo "<div class='contenidosTXT'>";
                                echo "<div class='tituloCont'>" . htmlspecialchars($contenido['Titulo']) . "</div>";
                                echo "<div class='respuestaCont'>" . htmlspecialchars($contenido['Descripcion']) . "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No se encontraron contenidos para este producto.</p>";
                        }
                        ?>
                    </div>
                    <div id="testimonios" class="testimonios oculto" style="display: none;">
                        <!-- Carrusel de testimonios -->
                        <?php
                        if ($testimonioResult->num_rows > 0) {
                            while ($testimonio = $testimonioResult->fetch_assoc()) {
                                echo "<div class='testimonial'>";
                                echo "<img class='fotoTestimonio' src='" . $testimonio["Foto"] . "' alt='" . $testimonio["Nombre"] .  "?'";
                                echo "<h2>" . htmlspecialchars($testimonio['Nombre']) . "</h2>";
                                echo "<h4>" . htmlspecialchars($testimonio['Subtitulo']) . "</h4>";
                                echo "<p>" . htmlspecialchars($testimonio['Descripcion']) . "</p>";
                                echo "</div>";
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
            <div class="cajaDerecha">
                <div id="carruselCoaches" class="carrusel">
                    <!-- Caja de un coach -->
                    <?php
                    if ($coachesResult->num_rows > 0) {
                        while ($coach = $coachesResult->fetch_assoc()) {
                            echo "<div class='coach'>";
                            echo "<img  src='" . $coach["Foto"] . "' alt='" . $coach["Nombre"] . ""  . $coach["Apellidos"] . "?'";
                            echo "<h2>" . htmlspecialchars($coach['Nombre']) . "</h2>";
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
            </div>
        </div>
    </main>

    <script src="../src/scripts/carruselProducto1.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>