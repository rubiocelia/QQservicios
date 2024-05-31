<!-- Este Script controla si el usuario sigue en la página o no  -->
<script>
        let heartbeatInterval;

        // Función para enviar latidos al servidor
        function sendHeartbeat() {
            fetch('heartbeat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_usuario=' + encodeURIComponent(sessionStorage.getItem('id_usuario'))
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Error al enviar el latido:', data.message);
                }
            })
            .catch(error => {
                console.error('Error al enviar el latido:', error);
            });
        }

        // Función para iniciar el envío de latidos
        function startHeartbeat() {
            if (!heartbeatInterval) {
                heartbeatInterval = setInterval(sendHeartbeat, 60000); // 1 minuto
            }
        }

        // Función para detener el envío de latidos
        function stopHeartbeat() {
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
                heartbeatInterval = null;
            }
        }

        // Manejar el evento de visibilidad de la página
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                startHeartbeat();
            } else {
                stopHeartbeat();
            }
        });

        // Iniciar el envío de latidos si la página está visible
        if (document.visibilityState === 'visible') {
            startHeartbeat();
        }

        // Guardar el ID del usuario en sessionStorage (por seguridad)
        sessionStorage.setItem('id_usuario', '<?php echo $_SESSION['id_usuario']; ?>');

        // Manejar el evento de cierre de pestaña
        window.addEventListener('beforeunload', function (event) {
            // Enviar un último latido antes de cerrar la pestaña
            navigator.sendBeacon('heartbeat.php', 'id_usuario=' + encodeURIComponent(sessionStorage.getItem('id_usuario')));
        });
    </script>

<!-- |||||||||||||||||||| footer.php |||||||||||||||||||| -->
<?php echo '<link rel="stylesheet" type="text/css" href="../src/estilos/css/menuFooter.css">'; ?>


<footer class="footer">
    <div class="footer-content">
        <div class="logoTexto">
            <img src="../src/archivos/logoQQ.png" alt="QuidQualitas Logo" class="footer-logo">
            <p class="footer-text">Quid Qualitas<br>Inspiring Customer Experiences</p>
            <div class="socials">
                <a href="#"><img class="rrss" src="../src/archivos/linkedin.png" alt="linkeding" target="_blank"></a>

                <a href="#"><img class="rrss" src="../src/archivos/instagram.png" alt="instagram" target="_blank"></a>

                <a href="#"><img class="rrss" src="../src/archivos/facebook.png" alt="facebook" target="_blank"></a>

                <a id="openPopupFooter"><img class="rrss" src="../src/archivos/llamada.png" alt="llamadas" target="_blank"></a>

            </div>
        </div>

        <div class="footer-section links">
            <h2>SITIO WEB</h2>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Coaches</a></li>
                <li><a href="#">Servicios</a></li>
                <li><a href="#">Quid Qualitas</a></li>
            </ul>
        </div>

        <div class="footer-section contact-form">
            <h2>CONTACTO</h2>
            <p><img class="iconoFooter" src="../src/archivos/ubicacion.png" alt="linkeding"> C. de Eugenio Salazar, 14,
                28002
                Madrid</p><br>
            <p><img class="iconoFooter" src="../src/archivos/correo.png" alt="linkeding">inspiring@quidqualitas.es</p>
            <br>
            <p><img class="iconoFooter" src="../src/archivos/telefono.png" alt="linkeding">+34 681 31 10 37</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>
            <a href="https://quidqualitas.es/aviso-legal/" target="_blank">Aviso legal | Protección de datos | Política
                de cookies</a>
        </p>
        <p>&copy;2022 Todos los derechos reservados - Quid Qualitas</p>
    </div>

    <div id="popupFooter">
    <div id="popupContentFooter">
      <img src="./archivos/contactanos.png" alt="Contactanos">
      <span id="closePopup">&times;</span>
    </div>
  </div>


</footer>