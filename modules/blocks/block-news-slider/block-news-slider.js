document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".listing-wrapper", {
    loop: false,
    autoHeight: true,
    slidesPerView: 1,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    autoplay: {
      delay: 3000,
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
      el: ".listing-wrapper .swiper-pagination",
      clickable: true,
    },
  });
});
