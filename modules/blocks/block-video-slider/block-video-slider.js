document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".video-slider", {
    loop: true,
    slidesPerView: 1.2,
    centeredSlides: true,
    spaceBetween: 32,
    // autoplay: {
    //   delay: 3000,
    // },
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
    // On init: play both the active and previous videos (if any)
    const activeSlide = this.slides[this.activeIndex];
    const prevSlide = this.activeIndex > 0 ? this.slides[this.activeIndex - 1] : null;

    [activeSlide, prevSlide].forEach((slide) => {
      if (!slide) return;
      const video = slide.querySelector("video");
      if (video) {
        video.load();
        video.play().catch((err) => {
          console.warn("Autoplay blocked on init:", err);
        });
      }
    });
  },

  transitionStart: function () {
    // Pause all videos during slide change
    document.querySelectorAll(".swiper-slide video").forEach((video) => {
      video.pause();
    });
  },

  transitionEnd: function () {
    // After transition, play active + previous videos
    const activeSlide = this.slides[this.activeIndex];
    const prevSlide = this.activeIndex > 0 ? this.slides[this.activeIndex - 1] : null;

    [activeSlide, prevSlide].forEach((slide) => {
      if (!slide) return;
      const video = slide.querySelector("video");
      if (video) {
        video.load();
        video.play().catch((err) => {
          console.warn("Autoplay blocked:", err);
        });
      }
    });
  },
},


    // loop: true,
    // spaceBetween: 20,
    // centeredSlides: true,
    // slidesPerView: 1.5, // main slide + small preview
    // navigation: {
    //     nextEl: '.swiper-button-next',
    //     prevEl: '.swiper-button-prev',
    // },
    // pagination: {
    //     el: '.swiper-pagination',
    //     clickable: true,
    // },
    // breakpoints: {
    //     768: { // tablet and up
    //         slidesPerView: 1.8 // bigger preview
    //     },
    //     1024: { // desktop
    //         slidesPerView: 2.2 // wider previews
    //     }
    // }
  });
});
