<!-- menu.php -->
<?php 
    echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/menuFooter.css">';
    include_once './bbdd/conecta.php'; // Incluir archivo de conexión a la base de datos

    // Verificar si el usuario está autenticado
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['id_usuario'])) {
        // Obtener el ID del usuario autenticado
        $idUsuario = $_SESSION['id_usuario'];

        // Realizar consulta para obtener el estado de administrador del usuario
        $conexion = getConexion();
        $query = "SELECT Administrador FROM DatosAcceso WHERE ID_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($administrador);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        // Determinar la URL de redirección basada en el estado de administrador
        $redirectURL = ($administrador == 1) ? "mi_cuenta_admin.php" : "mi_cuenta.php";
    } else {
        // Si el usuario no está autenticado, redirigir a la página de inicio de sesión
        $redirectURL = "formulario_inicio_sesion.php";
    }
?>

<header class="header">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
    <a href="index.php"><img class="logo" src="../src/archivos/QQAzul.png" alt="logoQQ" class="logo"></a>
    <nav>

        <button class="hamburger" aria-label="Abrir menú">☰</button>

        <ul class="menu">
            <li> <a href="index.php">Inicio</a></li>

            <li> <a href="servicios.php">Servicios</a></li>

            <li> <a href="coaches.php">Método</a></li>

            <li> <a href="https://quidqualitas.es/" target="_blank">QuidQualitas</a></li>

            <li> <a href="contacto.php">Contacto</a></li>

        </ul>

        <!-- Botones -->
        <div class="inicioRegistro">
            <li class="iniciarSesion" id="loginBtn"><a href="<?php echo $redirectURL; ?>">Mi Cuenta</a></li>
        </div>

    </nav>
</header>