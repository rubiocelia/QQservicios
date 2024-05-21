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
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
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
                <h2>Puedes encontrarnos en:</h2>
                <p>
                    <ion-icon name="time-outline"></ion-icon>
                    Lunes-Viernes: 10:00-14:00h

                </p>
                <p>
                    <ion-icon name="business-outline"></ion-icon>
                    C. de Eugenio Salazar, 14, 28002 Madrid
                </p>
                <p>
                    <ion-icon name="call-outline"></ion-icon>
                    +34 681 31 10 37
                </p>
                <p>
                    <ion-icon name="mail-open-outline"></ion-icon>
                    inspiring@quidqualitas.es
                </p>
                <div class="mapa">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3036.612031387825!2d-3.6723748846068554!3d40.4425225793624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4228fa137a6753%3A0x2b8b9f81e5f37461!2sC.%20de%20Eugenio%20Salazar%2C%2014%2C%2028002%20Madrid%2C%20Spain!5e0!3m2!1sen!2ses!4v1629814148106!5m2!1sen!2ses"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </section>

            <section class="contacto-formulario">
                <form action="procesar_contacto.php" method="post">
                    <div class="form-group">
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre..."
                            value="<?php echo $nombre; ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Correo elctrónico..."
                            value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <textarea id="mensaje" name="mensaje" rows="5" placeholder="¿En qué podemos ayudarte?"
                            required></textarea>
                    </div>
                    <button class="btnContacto" type="submit">Enviar</button>
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