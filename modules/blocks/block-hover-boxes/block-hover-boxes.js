document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".hover-boxes .box").forEach((box) => {
    const paragraphs = box.querySelectorAll(".box-content p");

    // initial state
    gsap.set(paragraphs, { autoAlpha: 0, y: 30, display: "none" });

    const tl = gsap.timeline({ paused: true });

    tl.to(paragraphs, {
      autoAlpha: 1,
      y: 0,
      duration: 0.6,
      ease: "power3.out",
      stagger: 0.15,
      pointerEvents: "auto",
      onStart: () => gsap.set(paragraphs, { display: "block" }),  // show instantly at start
      onReverseComplete: () => gsap.set(paragraphs, { display: "none" }) // hide instantly when done reversing
    });

    box.addEventListener("mouseenter", () => tl.play());
    box.addEventListener("mouseleave", () => tl.reverse());
  });
});
