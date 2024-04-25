// scripts.js
$(document).ready(function () {
  function showNextTestimonial() {
    var current = $(".active");
    var next = current.next(".testimonial-item");
    if (next.length === 0) {
      next = $(".testimonial-item").first();
    }
    current.removeClass("active");
    next.addClass("active");
  }

  function showPrevTestimonial() {
    var current = $(".active");
    var prev = current.prev(".testimonial-item");
    if (prev.length === 0) {
      prev = $(".testimonial-item").last();
    }
    current.removeClass("active");
    prev.addClass("active");
  }

  $(".next").click(showNextTestimonial);
  $(".prev").click(showPrevTestimonial);
});
