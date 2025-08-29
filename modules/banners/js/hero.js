document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("home")) {
    const heroElements = document.querySelectorAll(
      ".home .hero-static, .home .hero-video"
    );

    heroElements.forEach((heroElement) => {
      const heroStaticImage = heroElement.querySelector(
        ".hero-static__image, .hero-video__image"
      );
      const iframe = heroElement.querySelector(
        ".hero-video__image .video-player, .hero-static__image .video-player"
      );

      if (heroStaticImage && iframe) {
        const updateIframeSize = () => {
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

        // Initial size set
        updateIframeSize();

        // Adjust the size on window resize
        window.addEventListener("resize", updateIframeSize);
      }
    });
  }


});
