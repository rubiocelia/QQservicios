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

// Obtener los archivos habilitados para el usuario
$sqlArchivos = "SELECT a.Ruta, a.Descripcion, a.Fecha, p.Nombre AS Producto 
               FROM ArchivosUsuarios a
               JOIN Productos p ON a.ID_Producto = p.ID
               WHERE a.ID_usuario = ? AND a.Deshabilitado = 0";
$stmtArchivos = $conexion->prepare($sqlArchivos);
$stmtArchivos->bind_param("i", $idUsuario);
$stmtArchivos->execute();
$resultadoArchivos = $stmtArchivos->get_result();

$archivosPorProducto = [];
while ($fila = $resultadoArchivos->fetch_assoc()) {
    $archivosPorProducto[$fila['Producto']][] = $fila;
}

// Obtener los servicios comprados por el usuario
$sqlServicios = "SELECT p.ID, p.Nombre, p.DescripcionCorta, p.Foto 
                 FROM Compra c
                 JOIN Productos p ON c.ID_Producto = p.ID
                 WHERE c.ID_usuario = ? AND c.Confirmacion = 1";
$stmtServicios = $conexion->prepare($sqlServicios);
$stmtServicios->bind_param("i", $idUsuario);
$stmtServicios->execute();
$resultadoServicios = $stmtServicios->get_result();

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
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
    <h1 class="bienvenido">Bienvenid@, <?php echo htmlspecialchars($usuario['Nombre']); ?></h1>
    <main>
        <div id="menu2">
            <ul>
                <li onclick="mostrarSeccion('perfil')"><img src="./archivos/perfil/usuario.png" alt="Icono de perfil"
                        class="iconoMenu">Mi perfil</li>
                <li onclick="mostrarSeccion('servicios')"><img src="./archivos/perfil/servicio.png"
                        alt="Icono de perfil" class="iconoMenu">Mis servicios</li>
                <li onclick="mostrarSeccion('archivos')"><img src="./archivos/perfil/archivo.png" alt="Icono de perfil"
                        class="iconoMenu">Mis archivos</li>
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

            <!-- Sección Mis Servicios -->
            <div id="servicios" class="seccion">
                <h1>Mis servicios</h1>
                <div class="servicios-container">
                    <?php if ($resultadoServicios->num_rows > 0): ?>
                    <?php while ($servicio = $resultadoServicios->fetch_assoc()): ?>
                    <div class="tarjeta-servicio">
                        <img src="<?php echo htmlspecialchars($servicio['Foto']); ?>" alt="Imagen de servicio">
                        <h3><?php echo htmlspecialchars($servicio['Nombre']); ?></h3>
                        <p>
                            <?php echo htmlspecialchars(substr($servicio['DescripcionCorta'], 0, 100)); ?>
                            <?php if (strlen($servicio['DescripcionCorta']) > 100): ?>
                            ... <a href="infoServicio.php?servicio=<?php echo $servicio['ID']; ?>"
                                class="read-more-link">Leer
                                más</a>
                            <?php endif; ?>
                        </p>
                        <a href="infoServicio.php?servicio=<?php echo $servicio['ID']; ?>" class="btn-detalle">Ver
                            detalles</a>
                    </div>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <p>No has adquirido ningún servicio.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sección Mis Archivos -->
            <div id="archivos" class="seccion" class="archivosCliente">
                <h1>Mis archivos</h1>
                <?php if (!empty($archivosPorProducto)): ?>
                <?php foreach ($archivosPorProducto as $producto => $archivos): ?>
                <h2><?php echo htmlspecialchars($producto); ?></h2>
                <table class="tablaArchivos">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archivos as $archivo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($archivo['Descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($archivo['Fecha']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($archivo['Ruta']); ?>" download>Descargar</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endforeach; ?>
                <?php else: ?>
                <p>No hay archivos disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/menuLateral.js"></script>
    <script src="./scripts/cerrarSesion.js"></script>
    <script src="./scripts/botonesPerfil.js"></script>
    <?php include('footer.php'); ?>
</body>

</html>