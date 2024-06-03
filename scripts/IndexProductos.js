document.addEventListener("DOMContentLoaded", (event) => {
  const carruselInner = document.querySelector(".carrusel-inner");
  const carruselItems = document.querySelectorAll(".carrusel-item");
  const prevButton = document.querySelector(".prev");
  const nextButton = document.querySelector(".next");
  let currentIndex = 0;
  const totalItems = carruselItems.length;
  const itemsPerView = 2;

  function updateCarrusel() {
    const offset = -currentIndex * (100 / itemsPerView);
    carruselInner.style.transform = `translateX(${offset}%)`;
  }

  prevButton.addEventListener("click", () => {
    if (currentIndex > 0) {
      currentIndex -= 1;
    } else {
      currentIndex = totalItems - itemsPerView;
    }
    updateCarrusel();
  });

  nextButton.addEventListener("click", () => {
    if (currentIndex < totalItems - itemsPerView) {
      currentIndex += 1;
    } else {
      currentIndex = 0;
    }
    updateCarrusel();
  });

  updateCarrusel();
});
