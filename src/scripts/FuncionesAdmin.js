function redireccionarAVistaCliente(idUsuario) {
  // Construir la URL con el ID del usuario como parámetro
  var url = "vistaClienteAdmin.php?id=" + idUsuario;
  // Redireccionar a vistaClienteAdmin.php con el ID del usuario en la URL
  window.location.href = url;
}

function redireccionarAVistaCoach(id) {
  window.location.href = "detalleCoach.php?id=" + id;
}

function mostrarFormulario() {
  document.getElementById("formularioNuevoCoach").style.display = "block";
}

function cancelarCoach() {
  document.getElementById("formularioNuevoCoach").style.display = "none";
}

//Insercción asincrona de los coaches
function insertarCoach() {
  var formData = new FormData(document.getElementById("formNuevoCoach"));

  fetch("./server/insertar_coach.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        Swal.fire("Éxito", "El coach ha sido añadido exitosamente.", "success");
        // Opcionalmente, recargar la página o actualizar la tabla
        location.reload();
      } else {
        Swal.fire("Error", data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Swal.fire("Error", "Hubo un problema al añadir el coach.", "error");
    });
}

function mostrarFormularioCarrusel() {
  document.getElementById("formularioCarrusel").style.display = "block";
}

function crearCarrusel() {
  var nombreCarrusel = document.getElementById("nombreCarrusel").value;

  fetch("./server/crear_carrusel.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ nombre: nombreCarrusel }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = "gestionar_carrusel.php?id=" + data.id;
      } else {
        alert("Error al crear el carrusel: " + data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function redireccionarAGestionarCarrusel(id) {
  window.location.href = "gestionar_carrusel.php?id=" + id;
}

function redireccionarATestimonio(id) {
  window.location.href = "detalleTestimonio.php?id=" + id;
}

function mostrarFormularioTestimonio() {
  var formulario = document.getElementById("formularioTestimonio");
  formulario.style.display =
    formulario.style.display === "none" ? "block" : "none";
}

function crearTestimonio() {
  var formData = new FormData(document.getElementById("formNuevoTestimonio"));

  fetch("./server/agregar_testimonio.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.reload();
      } else {
        alert("Error al crear el testimonio: " + data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function redireccionarAVerGaleria(idGaleria) {
  window.location.href = "ver_galeria.php?id=" + idGaleria;
}

function crearNuevaGaleria() {
  let nombreGaleria = prompt("Introduce el nombre de la nueva galería:");
  if (nombreGaleria) {
    window.location.href =
      "./server/crear_galeria.php?nombre=" + encodeURIComponent(nombreGaleria);
  }
}
