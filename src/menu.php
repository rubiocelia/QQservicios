<!-- Lógica AJAX Inicio de sesión -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $(".FormularioLogin").submit(function(event) {
        event.preventDefault(); // Evitar el envío del formulario estándar

        var form_data = $(this).serialize(); // Serializar datos del formulario
        $.ajax({
            type: "POST",
            url: "./server/verificar_inicio_sesion.php", // Ruta al archivo PHP que maneja la verificación
            data: form_data,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Redirigir al usuario a su cuenta si las credenciales son válidas
                    window.location.href = response.redirect;
                } else {
                    // Mostrar mensaje de error si las credenciales son inválidas
                    $(".mensajeError").text(response.message);
                }
            }
        });
    });
});
</script>


<!-- menu.php -->
<?php echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/menuFooter.css">';
echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/PopUpLoginSignUp.css">'; ?>
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
            <div class="PanelIzquierdo"><img src="./archivos/FondoLogin.png" alt="Fondo del Login"></div>
            <div class="PanelDerecho">
                <div class="linksReedireccion">
                    <div class="Loginvolver">
                        <a id="volverBtn">
                            ← Volver</a>
                    </div>
                    <div class="loginJoin">¿Aún no eres miembro? <a id="JoinNow">Regístrate</a></div>
                </div>
                <div class="CuerpoLogin">
                    <h2 class="tituloBienvenido">¡BIENVENIDO DE VUELTA!</h2>
                    <h3 class="Subtitulo">Inicie sesión para continuar</h3>
                    <form class="FormularioLogin" action="/submit-your-form-handler" method="POST"
                        enctype="multipart/form-data">
                        <div>
                            <input type="email" class="inputLogin" name="correo_electronico"
                                placeholder="example@correo.es">
                        </div>
                        <div class="password">
                            <input type="password" class="inputLogin" name="contrasena" id="password"
                                placeholder="**********">
                            <img src="./archivos/ojo_cerrado.png" onclick="togglePassword()" class="pass-icon"
                                id="pass-icon">
                        </div>
                        <div class="LinkOlvidarPassword">
                            <p>¿Se te ha olvidado la contraseña?</p>
                        </div>

                        <div>
                            <button class="botonAcceder" type="submit">Acceder</button>
                        </div>
                        <div class="mensajeError" style="color: red; font-weight: bold;"></div>

                    </form>
                </div>
            </div>
            <span class="close">&times;</span>
        </div>
    </div>

    <!-- Registrarse -->
    <div id="registerPopup" class="popup">
        <div class="popup-content">
            <div class="PanelIzquierdo"><img src="./archivos/FondoLogin.png" alt="Fondo del Login"></div>
            <div class="PanelDerecho">
                <div class="linksReedireccion">
                    <div class="Loginvolver">
                        <a id="volverBtnRegistrarse">
                            ← Volver</a>
                    </div>
                    <div class="loginJoin">¿Ya eres miembro? <a id="loginRedireccion">Inicia Sesión</a></div>
                </div>
                <div class="CuerpoSign">
                    <h1 class="tituloUnete">Únete a nuestra comunidad</h1>
                    <h3 class="Subtitulo">Regístrate para descubrir cómo podemos ayudarte a alcanzar tus objetivos
                    </h3>
                    <form class="FormularioRegistro" action="./server/insertar_datos_registro_formulario.php"
                        method="POST" enctype="multipart/form-data">
                        <div class="formularioRegistroFlex">
                            <div class="columnaPrimeraFormularioRegistro">
                                <input type="text" class="inputLogin" name="Nombre" placeholder="Nombre">
                                <input type="text" class="inputLogin" name="Apellidos" placeholder="Apellidos">
                                <input type="email" class="inputLogin" name="correo_electronico"
                                    placeholder="example@email.es">
                            </div>
                            <div class="columnaSegundaFormularioRegistro">
                                <input type="number" class="inputLogin" name="NumTel" placeholder="Teléfono">
                                <div class="password">
                                    <input type="password" class="inputLogin" name="contrasena" id="password-Registro"
                                        placeholder="**********">
                                    <img src="./archivos/ojo_cerrado.png" onclick="togglePasswordRegistro()"
                                        class="pass-icon" id="pass-icon-Registro">
                                </div>
                                <input type="text" class="inputLogin" name="Organizacion" placeholder="Organización">
                            </div>
                        </div>
                        <div>
                            <button class="botonAcceder" type="submit">Registrarse</button>
                        </div>

                    </form>
                </div>
            </div>
            <span class="close">&times;</span>
        </div>
    </div>


    <script>
    function togglePassword() {
        var passwordInput = document.getElementById("password");
        var passIcon = document.getElementById("pass-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passIcon.src = "./archivos/ojo_abierto.png";
            passIcon.alt = "Ocultar Contraseña";
        } else {
            passwordInput.type = "password";
            passIcon.src = "./archivos/ojo_cerrado.png";
            passIcon.alt = "Mostrar Contraseña";
        }
    }

    function togglePasswordRegistro() {
        var passwordInput = document.getElementById("password-Registro");
        var passIcon = document.getElementById("pass-icon-Registro");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passIcon.src = "./archivos/ojo_abierto.png";
            passIcon.alt = "Ocultar Contraseña";
        } else {
            passwordInput.type = "password";
            passIcon.src = "./archivos/ojo_cerrado.png";
            passIcon.alt = "Mostrar Contraseña";
        }
    }
    </script>

</header>
</header>