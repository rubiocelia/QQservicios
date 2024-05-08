// Obtener elementos del DOM
var loginBtn = document.getElementById("loginBtn");
var loginPopup = document.getElementById("loginPopup");
var volver = document.getElementById("volverBtn");
var volverBtnRegistrarse = document.getElementById("volverBtnRegistrarse");
var loginRedireccion = document.getElementById("loginRedireccion");
var registerBtn = document.getElementById("registerBtn");
var registerPopup = document.getElementById("registerPopup");
var closeLoginBtn = loginPopup.querySelector(".close");
var closeRegisterBtn = registerPopup.querySelector(".close");
var joinNow = document.getElementById("JoinNow");
var closePopup = document.getElementById("closePopup");
var btnUnete = document.getElementById("btnUnete");

// Función para mostrar el popup con transición
function mostrarPopup(popup) {
  popup.style.display = "block";
  setTimeout(function () {
    popup.style.opacity = "1"; // Cambia la opacidad para mostrar gradualmente el popup
  }, 50); // Se agrega un pequeño retraso para asegurar que la transición se aplique correctamente
}

// Función para ocultar el popup con transición
function ocultarPopup(popup) {
  popup.style.opacity = "0"; // Cambia la opacidad para ocultar gradualmente el popup
  setTimeout(function () {
    popup.style.display = "none";
  }, 300); // Espera 300 milisegundos para ocultar el popup después de la transición
}

// Mostrar el popup de inicio de sesión cuando se haga clic en el botón de inicio de sesión
loginBtn.onclick = function () {
  mostrarPopup(loginPopup);
};

// Mostrar el popup de registro cuando se haga clic en el botón de registro
registerBtn.onclick = function () {
  mostrarPopup(registerPopup);
};

// Ocultar el popup de inicio de sesión cuando se haga clic en el botón de cierre
closeLoginBtn.onclick = function () {
  ocultarPopup(loginPopup);
};

// Ocultar el popup de registro cuando se haga clic en el botón de cierre
closeRegisterBtn.onclick = function () {
  ocultarPopup(registerPopup);
};

// Ocultar los popups cuando se haga clic fuera de ellos
window.onclick = function (event) {
  if (event.target == loginPopup) {
    ocultarPopup(loginPopup);
  }

  if (event.target == registerPopup) {
    ocultarPopup(registerPopup);
  }
};

// Cambiar de Login a Registrarse
joinNow.onclick = function () {
  mostrarPopup(registerPopup);
  ocultarPopup(loginPopup);
};

// Cambiar de Registrarse a Login
loginRedireccion.onclick = function () {
  mostrarPopup(loginPopup);
  ocultarPopup(registerPopup);
};
// Ocultar el popup cuando se haga clic en el botón de volver
volver.onclick = function () {
  ocultarPopup(loginPopup);
};

// Ocultar el popup cuando se haga clic en el botón de volver desde el popup de registro
volverBtnRegistrarse.onclick = function () {
  ocultarPopup(registerPopup);
};

// Mostrar el popup de registro cuando se haga clic en el botón de registro
btnUnete.onclick = function () {
  mostrarPopup(registerPopup);
};

document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.querySelector(".toggle-password");
  const passwordField = document.getElementById("password-field");

  togglePassword.addEventListener("click", function () {
    const type =
      passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);
    // Cambiar el emoji según el tipo
    this.textContent = type === "password" ? "Ver" : "No ver";
  });
});
