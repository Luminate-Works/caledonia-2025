document.addEventListener("DOMContentLoaded", function () {
  if (document.querySelector(".large-cta")) {
    const largeCTAElements = document.querySelectorAll(".large-cta");

    largeCTAElements.forEach((largeCTAElement) => {
      const largeCTAImage = largeCTAElement.querySelector(".video-overlay");
      const largeCTAIframe = largeCTAElement.querySelector(
        ".video-container iframe"
      );

      if (largeCTAImage && largeCTAIframe) {
        const updateLargeCTAIframeSize = () => {
          const containerWidth = largeCTAImage.offsetWidth;
          const containerHeight = largeCTAImage.offsetHeight;
          const videoAspectRatio = 16 / 9;
          const containerAspectRatio = containerWidth / containerHeight;

          if (containerAspectRatio > videoAspectRatio) {
            largeCTAIframe.style.width = `${containerWidth}px`;
            largeCTAIframe.style.height = `${
              containerWidth / videoAspectRatio
            }px`;
          } else {
            largeCTAIframe.style.height = `${containerHeight}px`;
            largeCTAIframe.style.width = `${
              containerHeight * videoAspectRatio
            }px`;
          }

          largeCTAIframe.style.top = "50%";
          largeCTAIframe.style.left = "50%";
          largeCTAIframe.style.transform = "translate(-50%, -50%)";
        };

        // Initial size set
        updateLargeCTAIframeSize();

        // Adjust the size on window resize
        window.addEventListener("resize", updateLargeCTAIframeSize);

        largeCTAIframe.addEventListener("load", function () {
          setTimeout(() => {
            largeCTAImage.classList.add("hidden");
          }, 800);
        });
      }
    });
  }
});
