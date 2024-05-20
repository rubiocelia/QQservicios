document.addEventListener("DOMContentLoaded", () => {
  const testimonials = document.querySelectorAll(".testimonial");
  const prevButton = document.querySelector(".prevTest");
  const nextButton = document.querySelector(".nextTest");
  let currentIndex = 0;

  function showTestimonial(index) {
    testimonials.forEach((testimonial, i) => {
      testimonial.classList.toggle("active", i === index);
    });
  }

  prevButton.addEventListener("click", () => {
    currentIndex =
      currentIndex === 0 ? testimonials.length - 1 : currentIndex - 1;
    showTestimonial(currentIndex);
  });

  nextButton.addEventListener("click", () => {
    currentIndex =
      currentIndex === testimonials.length - 1 ? 0 : currentIndex + 1;
    showTestimonial(currentIndex);
  });

  showTestimonial(currentIndex);
});

// datos
document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".count");
  const duration = 3000; // Duración en milisegundos para completar la cuenta
  const updateInterval = 50; // Intervalo de actualización en milisegundos

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const startTime = performance.now();

          counters.forEach((counter) => {
            const target = +counter.getAttribute("data-target");
            const increment = target / (duration / updateInterval);

            const updateCount = () => {
              const elapsedTime = performance.now() - startTime;
              const progress = elapsedTime / duration;
              const currentCount = Math.min(
                target,
                Math.floor(progress * target)
              );

              counter.innerText = "+" + currentCount;

              if (elapsedTime < duration) {
                setTimeout(updateCount, updateInterval);
              } else {
                counter.innerText = "+" + target;
              }
            };

            updateCount();
          });

          observer.disconnect();
        }
      });
    },
    { threshold: 1.0 }
  );

  counters.forEach((counter) => {
    observer.observe(counter);
  });
});
