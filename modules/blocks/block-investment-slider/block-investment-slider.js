document.addEventListener("DOMContentLoaded", function () {
  const swiper = new Swiper(".investment-slider", {
    loop: false,
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
    slidesPerView: "auto",
    spaceBetween: 30,
    pagination: { el: ".swiper-pagination", clickable: true },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    on: {
      slideChange: function () {
        updateBgFromActive(this);
      },
    },
  });

  updateBgFromActive(swiper); // initial background

  function updateBgFromActive(swiperInstance) {
    const activeSlide = swiperInstance.slides[swiperInstance.activeIndex];
    if (!activeSlide) return;

    const bgDiv = activeSlide.querySelector(".investment-meta");
    if (!bgDiv) return;

    const bgImage = window.getComputedStyle(bgDiv).backgroundImage;
    if (!bgImage || bgImage === "none") return;

    // Find nearest blur group
    const sliderEl = activeSlide.closest(".investment-slider");
    const group = sliderEl
      ? sliderEl.closest(".wp-block-group.blur")
      : activeSlide.closest(".wp-block-group.blur");
    if (!group) return;

    // Change the group's background image directly
    group.style.backgroundImage = bgImage;
    group.style.backgroundSize = "cover";
    group.style.backgroundPosition = "center";

    // Create the backdrop overlay if it doesn't exist
    let overlay = group.querySelector(".backdrop-blur-overlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.className = "backdrop-blur-overlay";
      Object.assign(overlay.style, {
        position: "absolute",
        inset: "0",
        zIndex: "0",
        pointerEvents: "none",
        backdropFilter: "blur(150px)",
        WebkitBackdropFilter: "blur(150px)", // Safari
        marginLeft: "0",
        marginRight: "0",
        maxWidth: "none",
      });

      // ensure group is relative
      if (window.getComputedStyle(group).position === "static") {
        group.style.position = "relative";
      }

      // insert overlay as first child
      group.insertBefore(overlay, group.firstChild);

      // ensure content is above overlay
      Array.from(group.children).forEach((child) => {
        if (child !== overlay) child.style.position = "relative";
      });
    }
  }
});
