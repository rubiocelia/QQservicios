function mostrarSeccion(id) {
  var secciones = document.getElementsByClassName("seccion");
  for (var i = 0; i < secciones.length; i++) {
    secciones[i].style.display = "none"; // Oculta todas las secciones
  }
  document.getElementById(id).style.display = "block"; // Muestra la sección seleccionada
}
document.addEventListener("DOMContentLoaded", function () {
  mostrarSeccion("perfil"); // Muestra 'Mi perfil' al cargar la página
});
