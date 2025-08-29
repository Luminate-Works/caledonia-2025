document.addEventListener("DOMContentLoaded", function () {
  const sections = document.querySelectorAll(".history-year-item");

  // Create stack effect for each section
  sections.forEach((section, index) => {
    const isLast = index === sections.length - 1;

    // Skip stack effect for the last item
    if (isLast) return;

    // Pin each section and create stack effect
    ScrollTrigger.create({
      trigger: section,
      start: "top top",
      end: isLast ? "bottom bottom" : () => `+=${section.offsetHeight + 200}`,
      pin: true,
      pinSpacing: false,
      scrub: 1,
      id: `stack-${index}`,
      onUpdate: (self) => {
        const progress = self.progress;

        // Stack scaling effect - sections get smaller as they move up
        const scale = Math.max(0.85, 1 - progress * 0.15);
        const yOffset = progress * -30;
        const rotationX = progress * 2;
        const brightness = Math.max(0.7, 1 - progress * 0.3);

        gsap.set(section, {
          scale: scale,
          y: yOffset,
          rotationX: rotationX,
          filter: `brightness(${brightness})`,
          transformOrigin: "center top",
        });
      },
    });

    // Content animation when section enters
    const contentElements = section.querySelectorAll(
      ".history-left > *, .history-images > *"
    );

    ScrollTrigger.create({
      trigger: section,
      start: "top 80%",
      once: true,
      onEnter: () => {
        gsap.fromTo(
          contentElements,
          {
            y: 60,
            opacity: 0,
            rotationY: 15,
          },
          {
            y: 0,
            opacity: 1,
            rotationY: 0,
            duration: 1.2,
            stagger: 0.15,
            ease: "power3.out",
          }
        );
      },
    });
  });
});
