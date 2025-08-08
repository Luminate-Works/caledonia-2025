document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".testimonial-carousel", {
    loop: false,
    autoHeight: true,

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
      el: ".swiper-pagination",
      clickable: true,
    },

    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },

    on: {
      init: function () {
        updateSlideCounter(this);
      },
      slideChange: function () {
        updateSlideCounter(this);
      },
    },
  });

  // slide counter
  function updateSlideCounter(swiper) {
    const activeSlide = swiper.slides[swiper.activeIndex];
    const currentSlideEl = activeSlide.querySelector(".current-slide");
    const totalSlidesEl = activeSlide.querySelector(".total-slides");

    if (currentSlideEl && totalSlidesEl) {
      const currentIndex = swiper.realIndex + 1;
      currentSlideEl.textContent = currentIndex.toString().padStart(2, "0");
    }
  }
});
