$(document).ready(function () {
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
  
    // Avanzar automáticamente cada 8 segundos
    setInterval(showNextCoach, 8000);
  
    // Observador de intersección para animar las fotos cuando entran en la vista
    const fotoObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeIn'); // Agrega clases de animación a la foto
            } else {
                entry.target.classList.remove('animate__animated', 'animate__fadeIn'); // Elimina clases de animación de la foto
            }
        });
    });
  
    const fotoTestimonios = document.querySelectorAll('.fotoTestimonio');
    fotoTestimonios.forEach(foto => {
        fotoObserver.observe(foto);
    });
  
    // Observador de intersección para animar el texto cuando entra en la vista
    const textObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeIn'); // Agrega clases de animación al texto
            } else {
                entry.target.classList.remove('animate__animated', 'animate__fadeIn'); // Elimina clases de animación del texto
            }
        });
    });
  
    const textSections = document.querySelectorAll('.testimonial-text');
    textSections.forEach(text => {
        textObserver.observe(text);
    });
  });
  