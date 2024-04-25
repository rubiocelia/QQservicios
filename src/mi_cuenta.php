<?php
        // Iniciar sesión
        session_start();

        // Verificar si el correo electrónico está almacenado en la sesión
        if (isset($_SESSION['correo_electronico'])) {
            // Si está almacenado, obtener el correo electrónico
            $correo_electronico = $_SESSION['correo_electronico'];
        } else {
            // Si el correo electrónico no está almacenado en la sesión, redirigir al usuario al formulario de inicio de sesión
            header("Location: formulario_inicio_sesion.php");
        }
        ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QQ Servicios</title>
    <link rel="stylesheet" type="text/css" href="../src/estilos/css/index.css">
    <link rel="icon" href="./archivos/QQAzul.ico" type="image/x-icon">
</head>

<body class="index">

    <?php include('menu_sesion_iniciada.php'); ?>
    <main>

    </main>
        <h1>Bienvenid@ <?php echo $correo_electronico?></h1>
        <style>
            .h1{
                color: white;
            }
        </style>
    <!-- JS de lógica para ocultarlo y mostrarlo -->
    <script src="./scripts/scriptPopUp.js"></script>
    <?php include('footer.php'); ?>

</body>

</html>
