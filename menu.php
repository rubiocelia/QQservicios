<!-- menu.php -->
<?php echo '<link rel="stylesheet" type="text/css" href="src/estilos/css/menuFooter.css">'; ?>
<header class="header">
    <a href="index.php"><img class="logo" src="src/archivos/logoQQ.png" alt="logoQQ" class="logo"></a>
    <nav>
        <button class="hamburger" aria-label="Abrir menú">☰</button>
        <ul class="menu">
            <li class="dropdown">
                <a href="QuienesSomos.php" class="dropbtn">¿Inicio</a>
            </li>

            <li class="dropdown">
                <a href="recursosGratuitos.php" class="dropbtn">Coaches</a>
            </li>

            <li class="dropdown">
                <a href="servicios.php" class="dropbtn">Servicios</a>
            </li>

            <li class="dropdown">
                <a href="retiros.php" class="dropbtn">QuidQualitas</a>
            </li>
            <!-- <?php if ($sesionActiva): ?>
            <li class="dropdown">
                <a href="perfil.php" class="dropbtn">Perfil</a>
                <div class="dropdown-content">
                    <a href="cerrarSesion.php">Cerrar sesión</a>
                </div>
            </li> -->
            <!-- <li class="botonResponsive">
                <a href="cerrarSesion.php">Cerrar sesión</a>
            </li> -->
            <!-- <?php endif; ?>
            <?php if (!$sesionActiva): ?> -->
            <div class="inicioRegistro">
                <li class="iniciarSesion"><a href="inicio_sesion.php">Iniciar sesión</a></li>
                <li class="registro"><a href="registrarse.php">Registrase</a></li>
            </div>

            <!-- <?php endif; ?> -->
        </ul>
    </nav>
</header>