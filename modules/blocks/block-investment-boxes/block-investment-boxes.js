document.addEventListener("DOMContentLoaded", function () {
  // Check if GSAP and ScrollTrigger are loaded
  if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
    // Register ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    // Stagger fade animation for home boxes
    if (document.querySelector(".lmn-investment-boxes")) {
      gsap.from(".lmn-investment-boxes .box", {
        opacity: 0,
        y: 30,
        stagger: 0.5,
        duration: 2,
        ease: "power2.out",
        scrollTrigger: {
          trigger: ".lmn-investment-boxes",
          start: "top 80%",
          toggleActions: "play none none none",
        },
      });
    }
  } else {
    console.warn("GSAP or ScrollTrigger is not loaded.");
  }
});
