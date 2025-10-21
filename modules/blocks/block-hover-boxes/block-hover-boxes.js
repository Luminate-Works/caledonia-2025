document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".hover-boxes .box").forEach((box) => {
    const paragraphs = box.querySelectorAll(".box-content p, .box-content .clone");
    const originalTitle = box.querySelector(".box-content h3.og");

    // initial state
    gsap.set(paragraphs, { autoAlpha: 0, y: 30, display: "none" });
    gsap.set(originalTitle, { autoAlpha: 1 }); // visible initially

    const tl = gsap.timeline({ paused: true });

    // Fade out original title immediately on hover
    tl.to(originalTitle, {
      autoAlpha: 0,
      duration: 0.3,
      ease: "power1.out"
    }, 0);

    // Animate paragraphs in
    tl.to(paragraphs, {
      autoAlpha: 1,
      y: 0,
      duration: 0.6,
      ease: "power3.out",
      stagger: 0.15,
      pointerEvents: "auto",
      onStart: () => gsap.set(paragraphs, { display: "block" }),
      onReverseComplete: () => {
        gsap.set(paragraphs, { display: "none" });

        // Fade OG back in after a short delay (e.g., 0.3s)
        gsap.to(originalTitle, { autoAlpha: 1, duration: 0.3, delay: 0.3 });
      }
    }, 0.3);

    box.addEventListener("mouseenter", () => tl.play());
    box.addEventListener("mouseleave", () => tl.reverse());
  });
});
