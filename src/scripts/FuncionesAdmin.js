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

function mostrarFormularioProducto() {
  Swal.fire({
    title: "Añadir Nuevo Producto",
    html: document.getElementById("formularioNuevoProducto").innerHTML,
    showCancelButton: true,
    confirmButtonText: "Guardar",
    customClass: "swal-wide",
    preConfirm: () => {
      const form = Swal.getPopup().querySelector("form");
      const formData = new FormData(form);
      return fetch("insertar_producto.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          if (data !== "Producto insertado correctamente") {
            throw new Error(data);
          }
          return data;
        })
        .catch((error) => {
          Swal.showValidationMessage(`Request failed: ${error}`);
        });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire("¡Éxito!", "El producto ha sido añadido.", "success").then(
        () => {
          location.reload();
        }
      );
    }
  });
}

document.getElementById("tipo").addEventListener("change", function () {
  var tipo = this.value;
  var campoLocal = document.getElementById("campoLocal");
  var campoYoutube = document.getElementById("campoYoutube");

  if (tipo === "foto" || tipo === "video_local") {
    campoLocal.style.display = "block";
    campoYoutube.style.display = "none";
  } else if (tipo === "video_youtube") {
    campoLocal.style.display = "none";
    campoYoutube.style.display = "block";
  } else {
    campoLocal.style.display = "none";
    campoYoutube.style.display = "none";
  }
});

function subirContenido() {
  var formData = new FormData(document.getElementById("formNuevoContenido"));

  fetch("subir_contenido.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      alert(data);
      const currentSection = getCurrentSectionId();
      actualizarSeccion(currentSection);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function mostrarFormularioContenido() {
  document.getElementById("formularioNuevoContenido").style.display = "block";
  actualizarCampos(); // Asegúrate de actualizar los campos al abrir el formulario
}

function actualizarCampos() {
  var tipo = document.getElementById("tipo").value;
  var campoLocal = document.getElementById("campoLocal");
  var campoYoutube = document.getElementById("campoYoutube");

  if (tipo === "foto" || tipo === "video_local") {
    campoLocal.style.display = "block";
    campoYoutube.style.display = "none";
  } else if (tipo === "video_youtube") {
    campoLocal.style.display = "none";
    campoYoutube.style.display = "block";
  } else {
    campoLocal.style.display = "none";
    campoYoutube.style.display = "none";
  }
}

function getCurrentSectionId() {
  var secciones = document.getElementsByClassName("seccion");
  for (var i = 0; i < secciones.length; i++) {
    if (secciones[i].style.display !== "none") {
      return secciones[i].id;
    }
  }
  return "perfil"; // Valor por defecto si no se encuentra ninguna sección visible
}

function eliminarContenido(id) {
  if (confirm("¿Seguro que deseas eliminar este contenido?")) {
    fetch("eliminar_contenido.php?id=" + id, {
      method: "GET",
    })
      .then((response) => response.text())
      .then((data) => {
        alert(data);
        const currentSection = getCurrentSectionId();
        actualizarSeccion(currentSection);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
}

function actualizarSeccion(id) {
  fetch(window.location.pathname + "?seccion=" + id)
    .then((response) => response.text())
    .then((data) => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(data, "text/html");
      const newContent = doc.getElementById(id).innerHTML;
      document.getElementById(id).innerHTML = newContent;
    })
    .catch((error) => {
      console.error("Error al actualizar la sección:", error);
    });
}

function mostrarSeccion(id) {
  var secciones = document.getElementsByClassName("seccion");
  for (var i = 0; i < secciones.length; i++) {
    secciones[i].style.display = "none"; // Oculta todas las secciones
  }
  document.getElementById(id).style.display = "block"; // Muestra la sección seleccionada
}

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const seccion = urlParams.get("seccion") || "perfil"; // Muestra 'perfil' por defecto si no hay parámetro
  mostrarSeccion(seccion);
});

function mostrarBibliotecaMedios() {
  document.getElementById("carrusel").style.display = "none";
  document.getElementById("bibliotecaMedios").style.display = "block";
}
