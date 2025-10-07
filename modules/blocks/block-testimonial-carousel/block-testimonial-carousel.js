document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".testimonial-carousel", {
    loop: false,
    autoHeight: true,
    slidesPerView: 1,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    breakpoints: {
      1024: {
        autoHeight: false,
      },
    },
    keyboard: {
      enabled: true,
      onlyInViewport: true,
    },
    pagination: {
      el: ".testimonial-carousel .swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".testimonial-carousel .swiper-button-next",
      prevEl: ".testimonial-carousel .swiper-button-prev",
    },
  });
});
