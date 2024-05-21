<?php
// Iniciar sesión
session_start();

// Inicializar variables para los datos del usuario
$nombre = "";
$email = "";

// Verificar si el ID de usuario está almacenado en la sesión
if (isset($_SESSION['id_usuario'])) {
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

    if ($resultado->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $resultado->fetch_assoc();
        $nombre = htmlspecialchars($usuario['Nombre']);
        $email = htmlspecialchars($usuario['Correo_electronico']);
    } else {
        // No se encontraron resultados, posible manejo de error o redirección
        echo "No se encontró información para el usuario con el ID proporcionado.";
        $conexion->close();
        exit();
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">

    <link rel="stylesheet" href="../src/estilos/css/contacto.css">
</head>

<body class="contacto">
    <header>
        <?php
        if (isset($_SESSION['id_usuario'])) {
            include('menu_sesion_iniciada.php');
        } else {
            include('menu.php');
        }
        ?>
    </header>

    <main class="contacto-main">
        <section class="contacto-hero">
            <img src="../src/archivos/contacto/comunicar.png" alt="Logo contacto" class="logoContacto">
            <h1>¡ Contacta con nosotros !</h1>
            <p>Si tienes alguna duda en relación a la plataforma, nuestros servicios o quieres lanzarnos una propuesta,
                no dudes en escribirnos, ¡somos todo oídos!</p>
        </section>

        <div class="cajasContacto">

            <section class="contacto-info">
                <h2>Información de Contacto</h2>
                <p>Puedes encontrarnos en:</p>
                <p><strong>Dirección:</strong> C. de Eugenio Salazar, 14, 28002 Madrid</p>
                <p><strong>Teléfono:</strong> +34 681 31 10 37</p>
                <p><strong>Email:</strong> inspiring@quidqualitas.es</p>
            </section>

            <section class="contacto-formulario">
                <h2>Formulario de Contacto</h2>
                <form action="procesar_contacto.php" method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </section>

        </div>
    </main>

    <footer>
        <?php include('footer.php'); ?>
    </footer>
    <script src="./scripts/scriptPopUp.js"></script>

</body>

</html>