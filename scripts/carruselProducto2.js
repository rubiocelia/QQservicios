document.addEventListener("DOMContentLoaded", function () {
  // Carrusel de fotos
  const carruselItems = document.querySelectorAll(".carrusel-item");
  const prevBtnNav = document.querySelector(".btnNav.prev");
  const nextBtnNav = document.querySelector(".btnNav.next");
  let carruselIndex = 0;

  function showCarruselItem(index) {
    carruselItems.forEach((item, i) => {
      item.classList.toggle("visible", i === index);
    });
  }

  function showNextCarruselItem() {
    carruselIndex = (carruselIndex + 1) % carruselItems.length;
    showCarruselItem(carruselIndex);
  }

  function showPrevCarruselItem() {
    carruselIndex =
      (carruselIndex - 1 + carruselItems.length) % carruselItems.length;
    showCarruselItem(carruselIndex);
  }

  prevBtnNav.addEventListener("click", showPrevCarruselItem);
  nextBtnNav.addEventListener("click", showNextCarruselItem);

  // Initialize the photo carousel
  showCarruselItem(carruselIndex);

  // Contenidos desplegables
  const tituloCont = document.querySelectorAll(".tituloCont");

  function closeAllContents() {
    const allContents = document.querySelectorAll(".respuestaCont");
    tituloCont.forEach((titulo) => titulo.classList.remove("active"));
    allContents.forEach((content) => (content.style.display = "none"));
  }

  tituloCont.forEach((titulo) => {
    titulo.addEventListener("click", function () {
      const respuesta = this.nextElementSibling;

      if (respuesta.style.display === "block") {
        respuesta.style.display = "none";
        this.classList.remove("active");
      } else {
        closeAllContents();
        respuesta.style.display = "block";
        this.classList.add("active");
      }
    });
  });

  // Carrusel de testimonios
  const testimonials = document.querySelectorAll(".testimonial");
  const prevTestBtn = document.querySelector(".prevTest");
  const nextTestBtn = document.querySelector(".nextTest");
  let testimonialIndex = 0;

  function showTestimonial(index) {
    testimonials.forEach((testimonial, i) => {
      testimonial.style.display = i === index ? "block" : "none";
    });
  }

  function showNextTestimonial() {
    testimonialIndex = (testimonialIndex + 1) % testimonials.length;
    showTestimonial(testimonialIndex);
  }

  function showPrevTestimonial() {
    testimonialIndex =
      (testimonialIndex - 1 + testimonials.length) % testimonials.length;
    showTestimonial(testimonialIndex);
  }

  prevTestBtn.addEventListener("click", showPrevTestimonial);
  nextTestBtn.addEventListener("click", showNextTestimonial);

  // Initialize the testimonial carousel
  showTestimonial(testimonialIndex);
});
