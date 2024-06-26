// Espera a que el DOM esté completamente cargado para ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencias a los elementos del DOM
  const btnModificar = document.getElementById("btnModificar");
  const btnGuardar = document.getElementById("btnGuardar");
  const btnCancelar = document.getElementById("btnCancelar");
  const btnSeleccionarFoto = document.getElementById("btnSeleccionarFoto");
  const fotoInput = document.getElementById("foto");
  const inputs = document.querySelectorAll(".perfil input, .perfil #foto");
  const fotoPerfil = document.querySelector(".fotoPerfil");
  const fotoOriginal = fotoPerfil.src; // Guarda el src original de la imagen al cargar la página

  // Activar la edición cuando se hace clic en el botón "Modificar"
  btnModificar.addEventListener("click", function () {
      inputs.forEach((input) => {
          if (input.type !== "file") input.readOnly = false; // Hace los inputs editables excepto el de archivo
          input.disabled = false; // Habilita todos los inputs, incluido el de archivo
      });

      // Cambiar la visibilidad de los botones
      btnGuardar.style.display = "inline-block";
      btnCancelar.style.display = "inline-block";
      btnModificar.style.display = "none";
      btnSeleccionarFoto.style.display = "inline-block"; // Mostrar el botón de cambiar foto
  });

  // Botón para seleccionar una nueva foto de perfil
  btnSeleccionarFoto.addEventListener("click", function () {
      fotoInput.click(); // Simula un click en el input de archivo real
  });

  // Previsualizar la nueva imagen seleccionada
  fotoInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              fotoPerfil.src = e.target.result; // Actualiza la imagen mostrada
          };
          reader.readAsDataURL(this.files[0]);
      }
  });

  // Cancelar la edición y restaurar los valores originales
  btnCancelar.addEventListener("click", function () {
      inputs.forEach((input) => {
          input.readOnly = true; // Pone los campos en modo de solo lectura
          input.disabled = input.type === "file"; // Deshabilita el input de archivo
      });

      // Restablece los valores del formulario a los últimos guardados
      document.querySelector("form").reset();

      // Restablece la imagen de perfil al valor original
      fotoPerfil.src = fotoOriginal;

      // Cambiar la visibilidad de los botones
      btnGuardar.style.display = "none";
      btnModificar.style.display = "inline-block";
      btnCancelar.style.display = "none";
      btnSeleccionarFoto.style.display = "none"; // Ocultar el botón de cambiar foto
  });

  // Guardar los cambios del perfil
  btnGuardar.addEventListener("click", function (event) {
      event.preventDefault(); // Previene la recarga de la página por el envío del formulario

      const formData = new FormData(document.querySelector("form"));

      // Enviar los datos del formulario al servidor
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
              inputs.forEach((input) => {
                  input.readOnly = true;
                  input.disabled = input.type === "file";
              });

              // Cambiar la visibilidad de los botones
              btnGuardar.style.display = "none";
              btnModificar.style.display = "inline-block";
              btnCancelar.style.display = "none";
              btnSeleccionarFoto.style.display = "none"; // Ocultar el botón de cambiar foto
          })
          .catch((error) => {
              console.error("Error:", error);
              alert(
                  "Hubo un error al guardar los cambios. Por favor, intenta nuevamente."
              );
          });
  });
});

// Función para cambiar entre mostrar u ocultar la contraseña
var a;
function pass(){
  if(a==1){
      document.getElementById('password').type='password';
      document.getElementById('pass-icon').src='./archivos/ojo_cerrado.png';
      a=0;
  }else{
      document.getElementById('password').type='text';
      document.getElementById('pass-icon').src='./archivos/ojo_abierto.png';
      a=1
  }
}
