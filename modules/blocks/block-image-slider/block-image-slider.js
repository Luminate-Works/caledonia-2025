document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".image-slider", {
    loop: true,
    slidesPerView: 1.4,
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
