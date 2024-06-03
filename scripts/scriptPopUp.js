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
var comprarBtn = document.getElementById("comprarBtn");
var comprarBtn2 = document.querySelector(".btnComprar2");

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
if (loginBtn) {
  loginBtn.onclick = function () {
    mostrarPopup(loginPopup);
  };
}

// Mostrar el popup de registro cuando se haga clic en el botón de registro
if (registerBtn) {
  registerBtn.onclick = function () {
    mostrarPopup(registerPopup);
  };
}

// Mostrar el popup de inicio de sesión cuando se haga clic en el botón de comprar
if (comprarBtn) {
  comprarBtn.onclick = function () {
    mostrarPopup(loginPopup);
  };
}

// Mostrar el popup de inicio de sesión cuando se haga clic en el botón de comprar en "¡Potencia tu futuro!"
if (comprarBtn2) {
  comprarBtn2.onclick = function () {
    mostrarPopup(loginPopup);
  };
}

// Ocultar el popup de inicio de sesión cuando se haga clic en el botón de cierre
if (closeLoginBtn) {
  closeLoginBtn.onclick = function () {
    ocultarPopup(loginPopup);
  };
}

// Ocultar el popup de registro cuando se haga clic en el botón de cierre
if (closeRegisterBtn) {
  closeRegisterBtn.onclick = function () {
    ocultarPopup(registerPopup);
  };
}

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
if (joinNow) {
  joinNow.onclick = function () {
    mostrarPopup(registerPopup);
    ocultarPopup(loginPopup);
  };
}

// Cambiar de Registrarse a Login
if (loginRedireccion) {
  loginRedireccion.onclick = function () {
    mostrarPopup(loginPopup);
    ocultarPopup(registerPopup);
  };
}

// Ocultar el popup cuando se haga clic en el botón de volver
if (volver) {
  volver.onclick = function () {
    ocultarPopup(loginPopup);
  };
}

// Ocultar el popup cuando se haga clic en el botón de volver desde el popup de registro
if (volverBtnRegistrarse) {
  volverBtnRegistrarse.onclick = function () {
    ocultarPopup(registerPopup);
  };
}

// Mostrar el popup de registro cuando se haga clic en el botón de registro
if (btnUnete) {
  btnUnete.onclick = function () {
    mostrarPopup(registerPopup);
  };
}

// Lógica AJAX para el inicio de sesión
$(document).ready(function () {
  $(".FormularioLogin").submit(function (event) {
    event.preventDefault(); // Evitar el envío del formulario estándar

    var form_data = $(this).serialize(); // Serializar datos del formulario
    $.ajax({
      type: "POST",
      url: "./server/verificar_inicio_sesion.php", // Ruta al archivo PHP que maneja la verificación
      data: form_data,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          if (response.redirect) {
            // Redirigir al usuario a la página del administrador
            window.location.href = response.redirect;
          } else {
            // Recargar la página actual para usuarios normales
            window.location.reload();
          }
        } else {
          // Mostrar mensaje de error si las credenciales son inválidas
          $(".mensajeError").text(response.message);
        }
      },
    });
  });
});
