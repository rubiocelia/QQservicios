document.addEventListener("DOMContentLoaded", function () {
  // Elementos de UI que serán manipulados
  const contenidosBtn = document.getElementById("contenidosBtn");
  const testimoniosBtn = document.getElementById("testimoniosBtn");
  const contenidosSection = document.getElementById("contenidos");
  const testimoniosSection = document.getElementById("testimonios");
  const allRespuestas = document.querySelectorAll(
    ".contenidosTXT .respuestaCont"
  );
  const allTitulos = document.querySelectorAll(".contenidosTXT .tituloCont");
  const items = document.querySelectorAll(".carrusel-item");
  const testimonials = document.querySelectorAll(".testimonial");

  // Inicializar el estado activo del botón de "Contenidos" y mostrar su sección
  setActiveButton(contenidosBtn);
  contenidosSection.style.display = "block";
  testimoniosSection.style.display = "none";

  // Funcionalidad para alternar las secciones y botones activos
  contenidosBtn.addEventListener("click", function () {
    setActiveButton(this);
    contenidosSection.style.display = "block";
    testimoniosSection.style.display = "none";
  });

  testimoniosBtn.addEventListener("click", function () {
    setActiveButton(this);
    contenidosSection.style.display = "none";
    testimoniosSection.style.display = "block";
    showCurrentTestimonial();
  });

  // Funcionalidad para los títulos desplegables de FAQ
  allTitulos.forEach((titulo) => {
    titulo.addEventListener("click", function () {
      const respuesta = this.nextElementSibling;
      const isOpen = respuesta.style.display === "block";
      allRespuestas.forEach((el) => (el.style.display = "none"));
      allTitulos.forEach((el) => el.classList.remove("active"));
      respuesta.style.display = isOpen ? "none" : "block";
      if (!isOpen) this.classList.add("active");
    });
  });

  // Funcionalidad para el carrusel de fotos y videos
  let currentIndex = 0;
  function updateCarousel() {
    items.forEach((item) => (item.style.display = "none"));
    items[currentIndex].style.display = "block";
  }

  document.querySelector(".btnNav.prev").addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateCarousel();
  });

  document.querySelector(".btnNav.next").addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateCarousel();
  });

  updateCarousel(); // Inicializa el carrusel

  // Funcionalidad para el carrusel de testimonios
  let currentTestimonial = 0;
  function showCurrentTestimonial() {
    testimonials.forEach((test) => (test.style.display = "none"));
    testimonials[currentTestimonial].style.display = "block";
  }

  document.querySelector(".prevTest").addEventListener("click", () => {
    currentTestimonial =
      (currentTestimonial - 1 + testimonials.length) % testimonials.length;
    showCurrentTestimonial();
  });

  document.querySelector(".nextTest").addEventListener("click", () => {
    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
    showCurrentTestimonial();
  });

  showCurrentTestimonial(); // Asegurarse de mostrar el testimonial actual al cargar

  // Función para establecer el botón activo
  function setActiveButton(activeBtn) {
    [contenidosBtn, testimoniosBtn].forEach((btn) =>
      btn.classList.remove("active")
    );
    activeBtn.classList.add("active");
  }
});

// carrusel coaches
document.addEventListener("DOMContentLoaded", function () {
  let currentCoachIndex = 0;
  const coaches = document.querySelectorAll(".coach");

  function showCurrentCoach() {
    coaches.forEach((coach, index) => {
      coach.classList.remove("visible");
      coach.style.display = "none";
      if (index === currentCoachIndex) {
        coach.classList.add("visible");
        coach.style.display = "flex";
      }
    });
  }

  document.querySelector(".prevCoaches").addEventListener("click", () => {
    currentCoachIndex =
      (currentCoachIndex - 1 + coaches.length) % coaches.length;
    showCurrentCoach();
  });

  document.querySelector(".nextCoaches").addEventListener("click", () => {
    currentCoachIndex = (currentCoachIndex + 1) % coaches.length;
    showCurrentCoach();
  });

  showCurrentCoach(); // Inicializa mostrando el primer coach
});

// animacion fotop y gif

document.addEventListener("DOMContentLoaded", () => {
  const button = document.querySelector(".btnComprar2");
  const staticImage = document.getElementById("staticImage");
  const animatedImage = document.getElementById("animatedImage");

  button.addEventListener("mouseover", () => {
    staticImage.classList.add("hidden");
    animatedImage.classList.remove("hidden");
  });

  button.addEventListener("mouseout", () => {
    animatedImage.classList.add("hidden");
    staticImage.classList.remove("hidden");
  });
});
