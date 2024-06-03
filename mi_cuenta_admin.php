<?php
// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="./estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="./estilos/css/miCuenta_admin.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
    <video autoplay muted loop id="backgroundVideo">
        <source src="./archivos/perfil/fondoPerfil2.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1 class="bienvenido">Bienvenid@ administrador, <?php echo htmlspecialchars($usuario['Nombre']); ?></h1>
    <main>
        <div id="menu2">
            <ul>
                <li onclick="mostrarSeccion('perfil')"><img src="./archivos/perfil/usuario.png" alt="Icono de perfil"
                        class="iconoMenu">Mi perfil</li>
                <li onclick="mostrarSeccion('clientes')"><img src="./archivos/perfil/clientes.png" alt="Icono de perfil"
                        class="iconoMenu">Clientes</li>
                <li onclick="mostrarSeccion('coaches')"><img src="./archivos/perfil/coaches.png" alt="Icono de perfil"
                        class="iconoMenu">Coaches</li>
                <li onclick="mostrarSeccion('servicios')"><img src="./archivos/perfil/servicio.png"
                        alt="Icono de perfil" class="iconoMenu">Servicios</li>
                <li onclick="mostrarSeccion('carrusel')"><img src="./archivos/perfil/carrusel.png" alt="Icono de perfil"
                        class="iconoMenu">Galerias</li>
                <li onclick="mostrarSeccion('testimonios')"><img src="./archivos/perfil/testimonios.png"
                        alt="Icono de perfil" class="iconoMenu">Testimonios</li>
                <li onclick="confirmarCerrarSesion()"><img src="./archivos/perfil/cerrar-sesion.png"
                        alt="Icono de cerrar sesion" class="iconoMenu">Cerrar sesión</li>
            </ul>
        </div>
        <div id="contenido">
            <!-- Sección Mi Perfil -->
            <div id="perfil" class="seccion">
                <h1>Mi perfil</h1>
                <form action="guardar_perfil.php" method="post" enctype="multipart/form-data">
                    <div class="perfil">
                        <div class="foto">
                            <img src="<?php echo htmlspecialchars($usuario['Foto']); ?>" alt="Foto de Perfil"
                                class="fotoPerfil">
                            <input type="file" id="foto" name="foto" style="display:none;">
                            <!-- Ocultamos el input real -->
                            <button type="button" id="btnSeleccionarFoto">Cambiar foto</button>
                            <!-- Botón estilizado para seleccionar foto -->
                        </div>
                        <div class="datos">
                            <!-- Fila para Nombre y Apellidos -->
                            <div class="fila">
                                <div class="campo">
                                    <label for="nombre">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre"
                                        value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" readonly>
                                </div>
                                <div class="campo">
                                    <label for="apellidos">Apellidos:</label>
                                    <input type="text" id="apellidos" name="apellidos"
                                        value="<?php echo htmlspecialchars($usuario['Apellidos']); ?>" readonly>
                                </div>
                            </div>
                            <!-- Fila para Email, Teléfono y Organización -->
                            <div class="fila">
                                <div class="campo">
                                    <label for="email">Correo electrónico:</label>
                                    <input type="email" id="email" name="email"
                                        value="<?php echo htmlspecialchars($usuario['Correo_electronico']); ?>"
                                        readonly>
                                </div>
                                <div class="campo">
                                    <label for="telefono">Número de teléfono:</label>
                                    <input type="tel" id="telefono" name="telefono"
                                        value="<?php echo htmlspecialchars($usuario['Numero_telefono']); ?>" readonly>
                                </div>
                                <div class="campo">
                                    <label for="organizacion">Organización:</label>
                                    <input type="text" id="organizacion" name="organizacion"
                                        value="<?php echo htmlspecialchars($usuario['Organizacion']); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="acciones">
                            <button type="button" id="btnModificar" onclick="habilitarEdicion()">Modificar</button>
                            <button type="submit" id="btnGuardar" style="display:none;">Guardar cambios</button>
                            <button type="button" id="btnCancelar" style="display:none;"
                                onclick="cancelarEdicion()">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Sección Clientes -->
            <div id="clientes" class="seccion">
                <h1>Mis Clientes</h1>
                <?php
                $conexion = getConexion();
                $query = "SELECT * FROM Usuarios";
                $resultado = $conexion->query($query);

                if ($resultado->num_rows > 0) {
                    echo '<table class="clientes-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Nombre</th>';
                    echo '<th>Apellido</th>';
                    echo '<th>Correo electrónico</th>';
                    echo '<th>Número de teléfono</th>';
                    echo '<th>Organización</th>';
                    echo '<th>Foto</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($cliente = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td onclick="redireccionarAVistaCliente(' . $cliente['ID'] . ')">' . $cliente['Nombre'] . '</td>';
                        echo '<td>' . $cliente['Apellidos'] . '</td>';
                        echo '<td>' . $cliente['Correo_electronico'] . '</td>';
                        echo '<td>' . $cliente['Numero_telefono'] . '</td>';
                        echo '<td>' . $cliente['Organizacion'] . '</td>';
                        echo '<td><img src="' . $cliente['Foto'] . '" alt="Foto de perfil"></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'No se encontraron clientes.';
                }

                $conexion->close();
                ?>
            </div>
            <!-- Sección Coaches -->
            <div id="coaches" class="seccion">
                <h1>Mis Coaches</h1>
                <?php
                $conexion = getConexion();
                $query = "SELECT * FROM Coaches";
                $resultado = $conexion->query($query);

                if ($resultado->num_rows > 0) {
                    echo '<table class="coaches-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Nombre</th>';
                    echo '<th>Apellidos</th>';
                    echo '<th>Titulo profesional</th>';
                    echo '<th>Foto</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($coach = $resultado->fetch_assoc()) {
                        echo '<tr onclick="redireccionarAVistaCoach(' . $coach['ID'] . ')">';
                        echo '<td>' . $coach['Nombre'] . '</td>';
                        echo '<td>' . $coach['Apellidos'] . '</td>';
                        echo '<td>' . $coach['Titulacion'] . '</td>';
                        echo '<td><img src="' . $coach['Foto'] . '" alt="Foto de perfil" width="50" height="50"></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'No se encontraron coaches.';
                }

                $conexion->close();
                ?>
                <button type="button" class="volver" onclick="mostrarFormulario()">Añadir Nuevo</button>
                <div id="formularioNuevoCoach" class="form-container" style="display: none;">
                    <form id="formNuevoCoach" class="styled-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="titulacion" class="form-label">Titulo profesional::</label>
                            <input type="text" id="titulacion" name="titulacion" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="linkedin" class="form-label">LinkedIn:</label>
                            <input type="url" id="linkedin" name="linkedin" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="video" class="form-label">Video:</label>
                            <input type="url" id="video" name="video" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="general" class="form-label">General:</label>
                            <input type="text" id="general" name="general" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="form-label">Foto:</label>
                            <input type="file" id="foto" name="foto" class="form-input" required>
                        </div>

                        <div class="form-button-container">
                            <button type="button" class="form-button" onclick="insertarCoach()">Enviar</button>
                            <button type="button" class="form-button-cancel" onclick="cancelarCoach()">Cancelar</button>
                        </div>
                    </form>
                </div>

            </div>
            <!-- Sección Carruseles -->
            <div id="carrusel" class="seccion">
                <h1>Mis Galerías</h1>
                <?php
                $conexion = getConexion();
                $query = "SELECT ID, Nombre_galeria FROM Galerias ORDER BY ID DESC";
                $resultado = $conexion->query($query);

                if ($resultado->num_rows > 0) {
                    echo '<table class="galerias-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Nombre de la Galería</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($galeria = $resultado->fetch_assoc()) {
                        echo '<tr onclick="redireccionarAVerGaleria(' . $galeria['ID'] . ')">';
                        echo '<td>' . htmlspecialchars($galeria['Nombre_galeria']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'No se encontraron galerías.';
                }
                ?>
                <button class="volver" onclick="crearNuevaGaleria()">Crear Nueva Galería</button>
                <button class="volver" onclick="mostrarBibliotecaMedios()">Biblioteca de Medios</button>
            </div>

            <!-- Sección Biblioteca de Medios -->
            <div id="bibliotecaMedios" class="seccion" style="display: none;">
                <h1>Biblioteca de Medios</h1>
                <?php
                $conexion = getConexion();
                $query = "SELECT * FROM ContenidoMultimedia ORDER BY ID DESC";
                $resultado = $conexion->query($query);

                if ($resultado->num_rows > 0) {
                    echo '<table class="medios-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Tipo</th>';
                    echo '<th>Contenido</th>';
                    echo '<th>Descripción</th>';
                    echo '<th>Acciones</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($contenido = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($contenido['Tipo']) . '</td>';
                        echo '<td>';
                        if ($contenido['Tipo'] == 'foto') {
                            echo '<img src="' . htmlspecialchars($contenido['URL_Local']) . '" alt="Foto" width="100">';
                        } elseif ($contenido['Tipo'] == 'video_local') {
                            echo '<video src="' . htmlspecialchars($contenido['URL_Local']) . '" width="100" controls></video>';
                        } elseif ($contenido['Tipo'] == 'video_youtube') {
                            parse_str(parse_url($contenido['URL_Youtube'], PHP_URL_QUERY), $youtubeParams);
                            $videoId = isset($youtubeParams['v']) ? $youtubeParams['v'] : '';
                            echo '<iframe src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" width="100" frameborder="0" allowfullscreen></iframe>';
                        }
                        echo '</td>';
                        echo '<td>' . htmlspecialchars($contenido['Descripcion']) . '</td>';
                        echo '<td><button onclick="eliminarContenido(' . $contenido['ID'] . ')">Eliminar</button></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'No se encontraron contenidos multimedia.';
                }
                ?>

                <button type="button" class="volver" onclick="mostrarFormularioContenido()">Añadir Nuevo</button>
                <div id="formularioNuevoContenido" class="form-container" style="display: none;">
                    <form id="formNuevoContenido" class="styled-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="tipo" class="form-label">Tipo de Contenido:</label>
                            <select id="tipo" name="tipo" class="form-input" required>
                                <option value="foto">Foto</option>
                                <option value="video_local">Video Local</option>
                                <option value="video_youtube">Video de YouTube</option>
                            </select>
                        </div>
                        <div class="form-group" id="campoLocal" style="display: none;">
                            <label for="url_local" class="form-label">Archivo Local:</label>
                            <input type="file" id="url_local" name="url_local" class="form-input">
                        </div>
                        <div class="form-group" id="campoYoutube" style="display: none;">
                            <label for="url_youtube" class="form-label">URL de YouTube:</label>
                            <input type="url" id="url_youtube" name="url_youtube" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" class="form-input" required></textarea>
                        </div>
                        <button type="button" onclick="subirContenido()" class="form-button">Subir Contenido</button>
                    </form>
                </div>




            </div>

            <!-- Sección Servicios -->
            <div id="servicios" class="seccion">
                <h1>Mis servicios</h1>
                <div class="servicios-container">
                    <?php
                    $conexion = getConexion();
                    $query = "SELECT * FROM Productos";
                    $resultado = $conexion->query($query);

                    if ($resultado->num_rows > 0) {
                        while ($producto = $resultado->fetch_assoc()) {
                            echo '<a href="editar_servicio.php?id=' . $producto['ID'] . '" class="servicio-box">';
                            echo '<h2>' . htmlspecialchars($producto['Nombre']) . '</h2>';
                            echo '<p>' . htmlspecialchars($producto['Precio']) . ' </p>';
                            echo '</a>';
                        }
                    } else {
                        echo 'No se encontraron servicios.';
                    }

                    $conexion->close();
                    ?>
                </div>

                <!-- Botón para añadir un nuevo producto -->
                <button type="button" class="volver" onclick="window.location.href='nuevo_servicio.php'">Añadir
                    Nuevo</button>
            </div>
            <!-- Sección Testimonios -->
            <div id="testimonios" class="seccion">
                <h1>Mis Testimonios</h1>
                <?php
                $query = "
                    SELECT t.ID, t.Nombre, t.Subtitulo, t.Descripcion, t.Foto, p.Nombre AS Producto
                    FROM Testimonios t
                    LEFT JOIN Productos p ON t.ID_Producto = p.ID;
                ";
                $conn = getConexion();
                $resultado = $conn->query($query);

                if ($resultado->num_rows > 0) {
                    echo '<table class="clientes-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Nombre</th>';
                    echo '<th>Subtitulo</th>';
                    echo '<th>Producto Asociado</th>';
                    echo '<th>Foto</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($testimonio = $resultado->fetch_assoc()) {
                        echo '<tr onclick="redireccionarATestimonio(' . $testimonio['ID'] . ')">';
                        echo '<td>' . htmlspecialchars($testimonio['Nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($testimonio['Subtitulo']) . '</td>';
                        echo '<td>' . htmlspecialchars($testimonio['Producto']) . '</td>';
                        echo '<td><img src="' . htmlspecialchars($testimonio['Foto']) . '" alt="Foto del testimonio" style="width:50px;height:50px;"></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo 'No se encontraron testimonios.';
                }
                ?>
                <button type="button" class="volver" onclick="mostrarFormularioTestimonio()">Añadir Nuevo</button>
                <div id="formularioTestimonio" style="display: none;">
                    <form id="formNuevoTestimonio" enctype="multipart/form-data">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="subtitulo">Subtitulo:</label>
                        <input type="text" id="subtitulo" name="subtitulo" required>

                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" required></textarea>

                        <label for="producto">Producto Asociado:</label>
                        <select id="producto" name="producto" required>
                            <?php
                            $productos = $conn->query("SELECT ID, Nombre FROM Productos");
                            while ($producto = $productos->fetch_assoc()) {
                                echo "<option value='" . $producto['ID'] . "'>" . $producto['Nombre'] . "</option>";
                            }
                            ?>
                        </select>

                        <label for="foto">Foto:</label>
                        <input type="file" id="foto" name="foto" required>

                        <button type="button" onclick="crearTestimonio()">Crear Testimonio</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/menuLateral.js"></script>
    <script src="./scripts/FuncionesAdmin.js"></script>
    <script src="./scripts/cerrarSesion.js"></script>
    <script src="./scripts/botonesPerfil.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>