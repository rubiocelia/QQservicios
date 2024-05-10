$(document).ready(function () {
  // Función para mostrar el siguiente testimonio
  function showNextTestimonial() {
    var current = $(".active"); // Selecciona el testimonio actualmente activo
    var next = current.next(".testimonial-item"); // Selecciona el siguiente testimonio
    // Si no hay siguiente testimonio, selecciona el primero
    if (next.length === 0) {
      next = $(".testimonial-item").first();
    }
    current.removeClass("active"); // Quita la clase 'active' del testimonio actual
    next.addClass("active"); // Añade la clase 'active' al siguiente testimonio
  }

  // Función para mostrar el testimonio anterior
  function showPrevTestimonial() {
    var current = $(".active"); // Selecciona el testimonio actualmente activo
    var prev = current.prev(".testimonial-item"); // Selecciona el testimonio anterior
    // Si no hay testimonio anterior, selecciona el último
    if (prev.length === 0) {
      prev = $(".testimonial-item").last();
    }
    current.removeClass("active"); // Quita la clase 'active' del testimonio actual
    prev.addClass("active"); // Añade la clase 'active' al testimonio anterior
  }

  // Asocia la función showNextTestimonial() al evento click del botón 'Siguiente'
  $(".next").click(showNextTestimonial);
  // Asocia la función showPrevTestimonial() al evento click del botón 'Anterior'
  $(".prev").click(showPrevTestimonial);
});
