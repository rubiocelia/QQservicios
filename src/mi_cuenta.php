<?php
    // Iniciar sesión
    session_start();

    // Verificar si el correo electrónico está almacenado en la sesión
    if (!isset($_SESSION['correo_electronico'])) {
        // Si el correo electrónico no está almacenado en la sesión, redirigir al usuario al formulario de inicio de sesión
        header("Location: formulario_inicio_sesion.php");
        exit();
    }

    // El correo electrónico del usuario está definido en la sesión
    $correoElectronico = $_SESSION['correo_electronico'];
    
    // Obtener los datos del usuario
    require_once("./bbdd/conecta.php");
    $conexion = getConexion();
    $sql = "SELECT * FROM Usuarios WHERE Correo_electronico = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correoElectronico);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        // No se encontraron resultados, posible manejo de error o redirección
        echo "No se encontró información para el usuario con el correo proporcionado.";
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
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/miCuenta.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="miCuenta">
    <?php include('menu_sesion_iniciada.php'); ?>
    <h1 class="bienvenido">Bienvenid@, <?php echo htmlspecialchars($usuario['Nombre']); ?></h1>
    <main>
        <div id="menu2">
            <ul>
                <li onclick="mostrarSeccion('perfil')">Mi perfil</li>
                <li onclick="mostrarSeccion('servicios')">Mis servicios</li>
                <li onclick="mostrarSeccion('archivos')">Mis archivos</li>
                <li onclick="mostrarSeccion('contacto')">Contacto</li>
            </ul>
        </div>
        <div id="contenido">
            <!-- Sección Mi Perfil -->
            <div id="perfil" class="seccion">
                <h1>Mi perfil</h1>
                <form action="guardar_perfil.php" method="post">
                    <img src="perfil.jpg" alt="Foto de Perfil" style="width:100px; height:100px;"><br>
                    <label for="nombre">Nombre:</label><br>
                    <input type="text" id="nombre" name="nombre"
                        value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" readonly><br>
                    <label for="apellidos">Apellidos:</label><br>
                    <input type="text" id="apellidos" name="apellidos"
                        value="<?php echo htmlspecialchars($usuario['Apellidos']); ?>" readonly><br>
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($usuario['Correo_electronico']); ?>" readonly><br>
                    <label for="telefono">Número de Teléfono:</label><br>
                    <input type="tel" id="telefono" name="telefono"
                        value="<?php echo htmlspecialchars($usuario['Numero_telefono']); ?>" readonly><br>
                    <!-- <label for="organizacion">Organización:</label><br>
                    <input type="text" id="organizacion" name="organizacion"
                        value="<?php echo htmlspecialchars($usuario['Organizacion']); ?>" readonly><br> -->

                    <button type="button2" onclick="habilitarEdicion()">Modificar</button>
                    <button type="submit" id="guardar" style="display:none;">Guardar Cambios</button>
                </form>
            </div>
            <div id="servicios" class="seccion">
                <h1>Mis servicios</h1>
            </div>

            <div id="archivos" class="seccion">
                <h1>Mis archivos</h1>
            </div>

            <div id="contacto" class="seccion">
                <h1>Contacto</h1>
                <form action="submit_contact.php" method="post" class="contact-form">

                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje:</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="./scripts/scriptPopUp.js"></script>
    <script src="./scripts/menuLateral.js"></script>
    <script>
    function habilitarEdicion() {
        document.getElementById('nombre').readOnly = false;
        document.getElementById('apellidos').readOnly = false;
        document.getElementById('email').readOnly = false;
        document.getElementById('telefono').readOnly = false;
        document.getElementById('organizacion').readOnly = false;
        document.getElementById('guardar').style.display = 'inline-block';
    }
    </script>
    <?php include('footer.php'); ?>
</body>

</html>