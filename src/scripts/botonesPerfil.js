function habilitarEdicion() {
  // Obtener todos los campos de entrada dentro del contenedor con clase "perfil"
  var campos = document.querySelectorAll(".perfil input");

  // Iterar sobre los campos y habilitar la edición
  campos.forEach(function (campo) {
    campo.readOnly = false; // Deshabilita el modo de solo lectura
  });

  // Mostrar el botón de guardar y ocultar el botón de modificar
  document.getElementById("btnGuardar").style.display = "block";
  document.getElementById("btnModificar").style.display = "none";
}

document
  .getElementById("btnGuardar")
  .addEventListener("click", function (event) {
    event.preventDefault(); // Previene la recarga de la página por el envío del formulario
    actualizarDatos();
  });

function actualizarDatos() {
  var formData = new FormData(document.querySelector("form"));

  fetch("guardar_perfil.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text()) // Cambiar a response.text() para obtener el texto plano
    .then((data) => {
      console.log(data); // Loguear la respuesta del servidor
      // Actualizar el estado de los campos y botones
      document.querySelectorAll(".perfil input").forEach(function (input) {
        input.readOnly = true; // Pone los campos de nuevo a solo lectura
      });
      document.getElementById("btnGuardar").style.display = "none"; // Oculta el botón Guardar
      document.getElementById("btnModificar").style.display = "block"; // Muestra el botón Modificar
    })
    .catch((error) => {
      console.error(error);
    });
}
