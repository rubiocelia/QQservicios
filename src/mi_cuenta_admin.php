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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta_admin.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <!-- CDN para el popup de cerrar sesión -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="miCuenta">
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1 class="bienvenido">Bienvenid@, <?php echo htmlspecialchars($usuario['Nombre']); ?></h1>
    <main>
        <div id="menu2">
            <ul>
                <li onclick="mostrarSeccion('perfil')"><img src="./archivos/perfil/usuario.png" alt="Icono de perfil" class="iconoMenu">Mi perfil</li>
                <li onclick="mostrarSeccion('clientes')"><img src="./archivos/perfil/clientes.png" alt="Icono de perfil" class="iconoMenu">Clientes</li>
                <li onclick="mostrarSeccion('archivos')"><img src="./archivos/perfil/coaches.png" alt="Icono de perfil" class="iconoMenu">Coaches</li>
                <li onclick="mostrarSeccion('contacto')"><img src="./archivos/perfil/servicio.png" alt="Icono de perfil" class="iconoMenu">Servicios</li>
                <li onclick="mostrarSeccion('contacto')"><img src="./archivos/perfil/carrusel.png" alt="Icono de perfil" class="iconoMenu">Carruseles</li>
                <li onclick="confirmarCerrarSesion()"><img src="./archivos/perfil/cerrar-sesion.png" alt="Icono de cerrar sesion" class="iconoMenu">Cerrar sesión</li>
            </ul>
        </div>
        <div id="contenido">
            <!-- Sección Mi Perfil -->
            <div id="perfil" class="seccion">
                <h1>Mi perfil</h1>
                <form action="guardar_perfil.php" method="post" enctype="multipart/form-data">
                    <div class="perfil">

                        <div class="foto">
                            <img src="<?php echo htmlspecialchars($usuario['Foto']); ?>" alt="Foto de Perfil" class="fotoPerfil">
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
                                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" readonly>
                                </div>
                                <div class="campo">
                                    <label for="apellidos">Apellidos:</label>
                                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['Apellidos']); ?>" readonly>
                                </div>
                            </div>

                            <!-- Fila para Email, Teléfono y Organización -->
                            <div class="fila">
                                <div class="campo">
                                    <label for="email">Correo electrónico:</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['Correo_electronico']); ?>" readonly>
                                </div>
                                <div class="campo">
                                    <label for="telefono">Número de teléfono:</label>
                                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['Numero_telefono']); ?>" readonly>
                                </div>
                                <div class="campo">
                                    <label for="organizacion">Organización:</label>
                                    <input type="text" id="organizacion" name="organizacion" value="<?php echo htmlspecialchars($usuario['Organizacion']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="acciones">
                            <button type="button" id="btnModificar" onclick="habilitarEdicion()">Modificar</button>
                            <button type="submit" id="btnGuardar" style="display:none;">Guardar cambios</button>
                            <button type="button" id="btnCancelar" style="display:none;" onclick="cancelarEdicion()">Cancelar</button>

                        </div>

                    </div>
                </form>

            </div>
            <div id="clientes" class="seccion">
                <h1>Clientes</h1>
                <?php
                // Obtener conexión a la base de datos
                $conexion = getConexion();

                // Consulta para obtener todos los clientes
                $query = "SELECT * FROM Usuarios";
                $resultado = $conexion->query($query);

                // Comprobar si hay resultados
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
                    // Iterar sobre los resultados y mostrar cada cliente en una fila de la tabla
                    while ($cliente = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $cliente['Nombre'] . '</td>';
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

                // Cerrar conexión
                $conexion->close();
                ?>
            </div>

            <div id="archivos" class="seccion">
                <h1>Mis archivos</h1>
            </div>

            <div id="contacto" class="seccion">
                <h1>Contacto</h1>
                <form action="enviarContacto.php" method="post" class="campoContacto">
                    <div class="contacto">
                        <div class="campoContacto">
                            <label for="name">Nombre:</label>
                            <input type="text" id="name" name="name" placeholder="Escribe tu nombre" required>
                        </div>
                        <div class="campoContacto">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="campoContacto">
                            <label for="message">Mensaje:</label>
                            <textarea id="message" name="message" placeholder="Escribe tu mensaje aquí..." required></textarea>
                        </div>
                        <button type="submit" class="btnEnviar">Enviar</button>
                    </div>
                </form>

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