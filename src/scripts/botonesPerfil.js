document.addEventListener("DOMContentLoaded", function () {
  // Botón para habilitar la edición
  const btnModificar = document.getElementById("btnModificar");
  btnModificar.addEventListener("click", function () {
    const inputs = document.querySelectorAll(".perfil input, .perfil #foto");
    inputs.forEach((input) => {
      input.readOnly = false; // Hace los inputs editables
      input.disabled = false; // Habilita el input de archivo
    });

    // Cambiar la visibilidad de los botones
    document.getElementById("btnGuardar").style.display = "inline-block";
    document.getElementById("btnCancelar").style.display = "inline-block";
    btnModificar.style.display = "none";
  });

  // Botón para cancelar la edición
  const btnCancelar = document.getElementById("btnCancelar");
  btnCancelar.addEventListener("click", function () {
    const inputs = document.querySelectorAll(".perfil input, .perfil #foto");
    inputs.forEach((input) => {
      input.readOnly = true; // Pone los campos en modo de solo lectura
      input.disabled = input.type === "file"; // Deshabilita el input de archivo
    });

    // Restablece los valores del formulario a los últimos guardados
    document.querySelector("form").reset();

    // Cambiar la visibilidad de los botones
    document.getElementById("btnGuardar").style.display = "none";
    document.getElementById("btnModificar").style.display = "inline-block";
    btnCancelar.style.display = "none";
  });

  // Botón para guardar los cambios
  const btnGuardar = document.getElementById("btnGuardar");
  btnGuardar.addEventListener("click", function (event) {
    event.preventDefault(); // Previene la recarga de la página por el envío del formulario

    const formData = new FormData(document.querySelector("form"));

    fetch("guardar_perfil.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text();
      })
      .then((data) => {
        console.log("Success:", data);

        // Reestablece el modo de solo lectura
        const inputs = document.querySelectorAll(
          ".perfil input, .perfil #foto"
        );
        inputs.forEach((input) => {
          input.readOnly = true; // Pone los campos en modo de solo lectura
          input.disabled = input.type === "file"; // Deshabilita el input de archivo
        });

        // Cambiar la visibilidad de los botones
        document.getElementById("btnGuardar").style.display = "none";
        document.getElementById("btnModificar").style.display = "inline-block";
        btnCancelar.style.display = "none";
      })
      .catch((error) => {
        console.error("Error:", error);
        alert(
          "Hubo un error al guardar los cambios. Por favor, intenta nuevamente."
        );
      });
  });
});
