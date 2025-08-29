document.addEventListener("DOMContentLoaded", () => {
  const testimonialCards = document.querySelectorAll(".testimonial-card");

  testimonialCards.forEach((card) => {
    const image = card.querySelector(".testimonial-image");
    const quoteWrapper = card.querySelector(".quote-wrapper");
    const quoteMark = card.querySelector(".quote-mark");
    const blockquote = card.querySelector("blockquote");
    const cite = card.querySelector("cite");

    // start hidden
    gsap.set([quoteWrapper, quoteMark, blockquote, cite], { opacity: 0, y: 30 });
    gsap.set(image, { opacity: 0, scale: 1.08 }); // slightly more zoom for punch

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: card,
        start: "top 80%",
      },
    });

    tl.to(image, {
      opacity: 1,
      scale: 1,
      duration: 0.9, // faster
      ease: "power2.out",
    })
      .to(quoteWrapper, { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }, "-=0.5")
      .to(quoteMark, { opacity: 1, y: 0, duration: 0.4, ease: "back.out(1.7)" }, "-=0.3")
      .to(blockquote, { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }, "-=0.2")
      .to(cite, { opacity: 1, y: 0, duration: 0.6, ease: "power2.out" }, "-=0.2");
  });
});