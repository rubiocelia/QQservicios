// Obtener el botón de inicio de sesión y el popup
var loginBtn = document.getElementById("loginBtn");
var loginPopup = document.getElementById("loginPopup");

//Botón volver
var volver = document.getElementById("volverBtn");

//
var volverRegistrar = document.getElementById("volverBtnRegistrarse");

//Botón JoinNow
var joinNow = document.getElementById("JoinNow");

// Obtener el botón de registro y el popup
var registerBtn = document.getElementById("registerBtn");
var registerPopup = document.getElementById("registerPopup");

// Obtener el elemento de cierre del popup de inicio de sesión
var closeLoginBtn = loginPopup.getElementsByClassName("close")[0];

// Obtener el elemento de cierre del popup de registro
var closeRegisterBtn = registerPopup.getElementsByClassName("close")[0];

// Mostrar el popup de inicio de sesión cuando se haga clic en el botón de inicio de sesión
loginBtn.onclick = function() {
    loginPopup.style.display = "block";
    setTimeout(function() {
        loginPopup.style.opacity = "1"; // Cambia la opacidad para mostrar gradualmente el popup
    }, 50); // Se agrega un pequeño retraso para asegurar que la transición se aplique correctamente
}

// Mostrar el popup de registro cuando se haga clic en el botón de registro
registerBtn.onclick = function() {
    registerPopup.style.display = "block";
    setTimeout(function() {
        registerPopup.style.opacity = "1"; // Cambia la opacidad para mostrar gradualmente el popup
    }, 50); // Se agrega un pequeño retraso para asegurar que la transición se aplique correctamente
}

// Ocultar el popup de inicio de sesión cuando se haga clic en el botón de cierre
closeLoginBtn.onclick = function() {
    loginPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
    setTimeout(function() {
        loginPopup.style.display = "none";
    }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
}

// Ocultar el popup de inicio de sesión cuando se haga clic en el botón de volver
volver.onclick = function() {
    loginPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
    setTimeout(function() {
        loginPopup.style.display = "none";
    }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
}

// Ocultar el popup de registro cuando se haga clic en el botón de cierre
closeRegisterBtn.onclick = function() {
    registerPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
    setTimeout(function() {
        registerPopup.style.display = "none";
    }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
}

// Función para cerrar los popups si se hace clic fuera de ellos
window.onclick = function(event) {
    if (event.target == loginPopup) {
        loginPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
        setTimeout(function() {
            loginPopup.style.display = "none";
        }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
    }

    if (event.target == registerPopup) {
        registerPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
        setTimeout(function() {
            registerPopup.style.display = "none";
        }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
    }
}

//Cambiar de Login a Registrarse
// Mostrar el popup de registro cuando se haga clic en el botón de Join Now
joinNow.onclick = function() {
    registerPopup.style.display = "block";
    setTimeout(function() {
        registerPopup.style.opacity = "1"; // Cambia la opacidad para mostrar gradualmente el popup
    }, 50); // Se agrega un pequeño retraso para asegurar que la transición se aplique correctamente

    loginPopup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
    setTimeout(function() {
        loginPopup.style.display = "none";
    }, 30); // Espera 300 milisegundos para ocultar el popup después de la transición
}