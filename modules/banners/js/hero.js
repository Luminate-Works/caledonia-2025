document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("home")) {
    const heroElements = document.querySelectorAll(
      ".home .hero-static, .home .hero-video"
    );

    heroElements.forEach((heroElement) => {
      const heroStaticImage = heroElement.querySelector(
        ".hero-static__image, .hero-video__image"
      );
      const iframes = heroElement.querySelectorAll(
        ".hero-video__image .video-player, .hero-static__image .video-player"
      );

      iframes.forEach((iframe) => {
        const updateIframeSize = () => {
          if (iframe.classList.contains("mobile-video")) {
            return;
          }

          // desktop video logic
          const containerWidth = heroStaticImage.offsetWidth;
          const containerHeight = heroStaticImage.offsetHeight;
          const videoAspectRatio = 16 / 9;
          const containerAspectRatio = containerWidth / containerHeight;

          if (containerAspectRatio > videoAspectRatio) {
            iframe.style.width = `${containerWidth}px`;
            iframe.style.height = `${containerWidth / videoAspectRatio}px`;
          } else {
            iframe.style.height = `${containerHeight}px`;
            iframe.style.width = `${containerHeight * videoAspectRatio}px`;
          }

          iframe.style.top = "50%";
          iframe.style.left = "50%";
          iframe.style.transform = "translate(-50%, -50%)";
        };

        updateIframeSize();
        window.addEventListener("resize", updateIframeSize);
      });
    });

    const swiper = new Swiper(".banner-slider-overlay", {
      loop: true,
      effect: "fade",
      fadeEffect: {
        crossFade: true,
      },
      slidesPerView: 1,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },

      pagination: {
        el: ".banner-slider-overlay .swiper-pagination",
        clickable: true,
        renderBullet: function (i, className) {
          return `
   <button class="${className}">
  <svg class="progress" width="24" height="24"><circle class="circle-origin" r="11.7" cx="12" cy="12"></circle></svg><span></span>
</button>
      `;
        },
      },
    });
  }
});
