$(document).ready(function () {
    var interval;
    function showNextCoach() {
        var current = $(".testimonial-slider .active");
        var next = current.next(".testimonial-item");
        if (next.length === 0) {
            next = $(".testimonial-slider .testimonial-item").first();
        }
        current.removeClass("active");
        next.addClass("active");
    }
  
    function showPrevCoach() {
        var current = $(".testimonial-slider .active");
        var prev = current.prev(".testimonial-item");
        if (prev.length === 0) {
            prev = $(".testimonial-slider .testimonial-item").last();
        }
        current.removeClass("active");
        prev.addClass("active");
    }
  
    $(".next").click(showNextCoach);
    $(".prev").click(showPrevCoach);
  
    function startAutoSlide() {
        interval = setInterval(showNextCoach, 8000);
    }

    function stopAutoSlide() {
        clearInterval(interval);
    }
  
    // Observador de intersección para controlar la visibilidad del carrusel
    const carouselObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                startAutoSlide();
            } else {
                stopAutoSlide();
            }
        });
    }, { threshold: 0.5 }); // Configuración de umbral para determinar cuánto debe estar visible el carrusel para activar el intervalo
  
    carouselObserver.observe(document.querySelector('.testimonial-slider'));

    // Observador de intersección para animar fotos y textos
    const animateOnView = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                entry.target.classList.remove('animate__animated', 'animate__fadeIn');
            }
        });
    });

    document.querySelectorAll('.fotoTestimonio, .testimonial-text').forEach(item => {
        animateOnView.observe(item);
    });
});
