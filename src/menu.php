    <!-- menu.php -->
    <?php echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/menuFooter.css">';
    echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/PopUpLoginSignUp.css">'; ?>
    <header class="header">

        <a href="index.php"><img class="logo" src="../src/archivos/logo.png" alt="logoQQ" class="logo"></a>
        <nav>

            <button class="hamburger" aria-label="Abrir menú">☰</button>

            <ul class="menu">
                <li> <a href="QuienesSomos.php">Inicio</a></li>

                <li> <a href="recursosGratuitos.php">Coaches</a></li>

                <li> <a href="servicios.php">Servicios</a></li>

                <li> <a href="retiros.php">QuidQualitas</a></li>
            </ul>

            <!-- Botones -->
            <div class="inicioRegistro">
                <li class="iniciarSesion" id="loginBtn"><a>Iniciar sesión</a></li>
                <li class="registro" id="registerBtn"><a>Registrarse</a></li>
            </div>

        </nav>

        <!-- Inicio de sesión -->
        <div id="loginPopup" class="popup">
            <div class="popup-content">
                <div class="PanelIzquierdo"> <img src="./archivos/FondoLogin.png" alt="Fondo del Login"></div>
                <div class="PanelDerecho">

                    <div class="linksReedireccion">
                        <div class="Loginvolver">
                            <a id="volverBtn">
                                < Volver</a>
                        </div>
                        <div class="loginJoin">¿Aún no eres miembro?<a id="JoinNow"> JOIN NOW</a></div>
                    </div>
                    <div class="CuerpoLogin">
                        <h2 class="tituloBienvenido">¡BIENVENIDO DE VUELTA!</h2>
                        <h3 class="Subtitulo">Inicie sesión para continuar</h3>
                        <form class="FormularioLogin" action="/submit-your-form-handler" method="POST" enctype="multipart/form-data">
                            <div>
                                <input type="email" id="inputLogin" name="correo_electronico" placeholder="example@correo.es" required>
                            </div>
                            <div>
                                <input type="password" id="inputLogin" name="contrasena" placeholder="**********" required>
                            </div>
                            <div>
                                <button class="botonAcceder" type="submit">Acceder</button>
                            </div>
                        </form>
                    </div>
                </div>
                <span class="close">&times;</span>
            </div>
        </div>


        <!-- Registrarse  -->
        <div id="registerPopup" class="popup">
            <div class="popup-content">
            <div class="PanelIzquierdo"> <img src="./archivos/FondoLogin.png" alt="Fondo del Login"></div>
                <div class="PanelDerecho">

                    <div class="linksReedireccion">
                        <div class="Loginvolver">
                            <a id="volverBtnRegistrarse">
                                < Volver</a>
                        </div>
                        <div class="loginJoin">¿Ya eres miembro?<a id="JoinNow"> Inicia Sesión</a></div>
                    </div>
                    <div class="CuerpoLogin">
                        <h1 class="tituloBienvenido">Únete a nuestra comunidad</h1>
                        <h3 class="Subtitulo">Registrate para descubrir cómo podemos ayudarte a alcanzar tus objetivos</h3>
                        <form class="FormularioLogin" action="/submit-your-form-handler" method="POST" enctype="multipart/form-data">
                            <div>
                                <input type="email" id="inputLogin" name="correo_electronico" placeholder="example@correo.es" required>
                            </div>
                            <div>
                                <input type="password" id="inputLogin" name="contrasena" placeholder="**********" required>
                            </div>
                            <div>
                                <button class="botonAcceder" type="submit">Acceder</button>
                            </div>
                        </form>
                    </div>
                </div>
                <span class="close">&times;</span>
        </div>

    </header>
    </header>