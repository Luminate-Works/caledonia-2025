gsap.registerPlugin(ScrollTrigger);
gsap.registerPlugin(SplitText);

document.addEventListener("DOMContentLoaded", function () {
  function createScrollTriggerAnimation(elements, settings) {
    elements.forEach((element) => {
      gsap.set(element, settings.initial);

      ScrollTrigger.create({
        trigger: element,
        start: "top 85%",
        interval: 0.2,
        once: true,
        onEnter: () => gsap.to(element, settings.onEnter),
        //markers: true
      });
    });
  }

  // Video lightbox featured image animation on scroll
  const videoBlocks = document.querySelectorAll(".block-video-lightbox");
  videoBlocks.forEach((block) => {
    const videoFeat = block.querySelector(".video-feat img");
    if (videoFeat) {
      gsap.set(videoFeat, { scale: 1 });

      ScrollTrigger.create({
        trigger: block,
        start: "top center",
        end: "bottom center",
        onEnter: () =>
          gsap.to(videoFeat, { scale: 1.15, duration: 1, ease: "power2.out" }),
        onLeave: () =>
          gsap.to(videoFeat, { scale: 1, duration: 1, ease: "power2.out" }),
        onEnterBack: () =>
          gsap.to(videoFeat, { scale: 1.15, duration: 1, ease: "power2.out" }),
        onLeaveBack: () =>
          gsap.to(videoFeat, { scale: 1, duration: 1, ease: "power2.out" }),
        // markers: true
      });
    }
  });

  // Cover image background animation on scroll
  const coverBlocks = document.querySelectorAll(".wp-block-cover, .scale");
  coverBlocks.forEach((block) => {
    const coverImage = block.querySelector(
      ".wp-block-cover__image-background, img"
    );
    if (coverImage) {
      gsap.set(coverImage, { scale: 1 });

      ScrollTrigger.create({
        trigger: block,
        start: "top center",
        end: "bottom center",
        onEnter: () =>
          gsap.to(coverImage, {
            scale: 1.035,
            duration: 3,
            ease: "power2.out",
          }),
        onLeave: () =>
          gsap.to(coverImage, { scale: 1, duration: 3, ease: "power2.out" }),
        onEnterBack: () =>
          gsap.to(coverImage, {
            scale: 1.035,
            duration: 3,
            ease: "power2.out",
          }),
        onLeaveBack: () =>
          gsap.to(coverImage, { scale: 1, duration: 3, ease: "power2.out" }),
      });
    }
  });

  // General fade-in page animations
  createScrollTriggerAnimation(
    document.querySelectorAll(
      "#sidebar, #content, .wp-block-group, #content > .wp-block-column, .wp-block-cover, .fade, .hero"
    ),
    {
      initial: { opacity: 0 },
      onEnter: { opacity: 1, duration: 1.5, ease: "power2.out" },
    }
  );

  // Fade in from left page animations
  createScrollTriggerAnimation(document.querySelectorAll(".fade-in-left"), {
    initial: { opacity: 0, x: -10 },
    onEnter: { opacity: 1, x: 0, duration: 1.5, ease: "power2.out" },
  });

  // Fade in and up for #scrolldown button
  const scrollDownButton = document.getElementById("scrolldown");
  const contentSection = document.querySelector("#content");
  if (scrollDownButton) {
    gsap.from(scrollDownButton, {
      opacity: 0,
      y: 10,
      duration: 1.5,
      delay: 0.55,
      ease: "power2.out",
    });
  }

  if (scrollDownButton && contentSection) {
    scrollDownButton.addEventListener("click", function (e) {
      e.preventDefault();
      const offsetTop =
        contentSection.getBoundingClientRect().top + window.pageYOffset;
      window.scrollTo({ top: offsetTop, behavior: "smooth" });
    });
  }

  // Refresh ScrollTrigger once everything is set up
  ScrollTrigger.refresh();


  // Intro text
  let split = SplitText.create(".is-style-intro", { type: "words, chars" });

  gsap.from(split.chars, {
    duration: 1,
    color: "#01003566",
    stagger: 0.05,
    ease: "power2.out",
    scrollTrigger: {
      trigger: ".is-style-intro",
      start: "top 80%",
      toggleActions: "play none none none",
    },
  });


});
