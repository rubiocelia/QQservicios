$(document).ready(function () {
    // Variable para almacenar el intervalo de tiempo para el desplazamiento automático
    var interval;

    // Función para mostrar el siguiente testimonio
    function showNextCoach() {
        var current = $(".testimonial-slider .active");
        var next = current.next(".testimonial-item");
        // Verificar si hay un siguiente testimonio
        if (next.length === 0) {
            // Si no hay, seleccionar el primer testimonio
            next = $(".testimonial-slider .testimonial-item").first();
        }
        // Quitar la clase 'active' del testimonio actual
        current.removeClass("active");
        // Añadir la clase 'active' al siguiente testimonio
        next.addClass("active");
    }

    // Función para mostrar el testimonio anterior
    function showPrevCoach() {
        var current = $(".testimonial-slider .active");
        var prev = current.prev(".testimonial-item");
        // Verificar si hay un testimonio anterior
        if (prev.length === 0) {
            // Si no hay, seleccionar el último testimonio
            prev = $(".testimonial-slider .testimonial-item").last();
        }
        // Quitar la clase 'active' del testimonio actual
        current.removeClass("active");
        // Añadir la clase 'active' al testimonio anterior
        prev.addClass("active");
    }

    // Asociar la función showNextCoach() al evento click del botón de siguiente
    $(".next").click(showNextCoach);
    // Asociar la función showPrevCoach() al evento click del botón de anterior
    $(".prev").click(showPrevCoach);

    // Función para iniciar el desplazamiento automático de los testimonios
    function startAutoSlide() {
        // Configurar un intervalo para llamar a showNextCoach() cada 8 segundos (8000 milisegundos)
        interval = setInterval(showNextCoach, 8000);
    }

    // Función para detener el desplazamiento automático de los testimonios
    function stopAutoSlide() {
        // Limpiar el intervalo establecido para detener el desplazamiento automático
        clearInterval(interval);
    }

    // Observador de intersección para controlar la visibilidad del carrusel y activar/desactivar el desplazamiento automático
    const carouselObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Si el carrusel es visible, iniciar el desplazamiento automático
                startAutoSlide();
            } else {
                // Si el carrusel no es visible, detener el desplazamiento automático
                stopAutoSlide();
            }
        });
    }, { threshold: 0.5 }); // Configuración del umbral de visibilidad

    // Observar el elemento '.testimonial-slider' para cambios en la intersección y activar/desactivar el desplazamiento automático
    carouselObserver.observe(document.querySelector('.testimonial-slider'));

    // Observador de intersección para animar las fotos y los textos al desplazarse hacia abajo
    const animateOnView = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Si el elemento es visible, agregar clases de animación
                entry.target.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                // Si el elemento no es visible, quitar las clases de animación
                entry.target.classList.remove('animate__animated', 'animate__fadeIn');
            }
        });
    });

    // Observar todos los elementos con clases '.fotoTestimonio' y '.testimonial-text' para animar al desplazarse hacia abajo
    document.querySelectorAll('.fotoTestimonio, .testimonial-text').forEach(item => {
        animateOnView.observe(item);
    });
});
