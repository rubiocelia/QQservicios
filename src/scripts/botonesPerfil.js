function habilitarEdicion() {
  document.querySelectorAll(".perfil input").forEach(function (input) {
    input.readOnly = false; // Deshabilita el modo de solo lectura
  });
  document.getElementById("btnGuardar").style.display = "block"; // Muestra el botón de guardar
  document.getElementById("btnModificar").style.display = "none"; // Oculta el botón de modificar
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
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log(data.message); // Mensaje de éxito
        // Reiniciar el estado de los campos y botones
        document.querySelectorAll(".perfil input").forEach(function (input) {
          input.readOnly = true; // Pone los campos de nuevo a solo lectura
        });
        document.getElementById("btnGuardar").style.display = "none"; // Oculta el botón Guardar
        document.getElementById("btnModificar").style.display = "block"; // Muestra el botón Modificar
      } else {
        console.error(data.message); // Mensaje de error
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
