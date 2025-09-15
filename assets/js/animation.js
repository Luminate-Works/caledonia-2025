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
      "#sidebar, #content > .wp-block-column, .wp-block-cover, .fade, .hero"
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

  // *
  // Intro Text animation
  //  is-style-intro
  // */

  // Intro text
  // let split = SplitText.create(".is-style-intro, .is-style-introborder", {
  //   type: "words, chars",
  // });

  // gsap.from(split.chars, {
  //   duration: 1,
  //   color: "#01003566",
  //   stagger: 0.05,
  //   ease: "power2.out",
  //   scrollTrigger: {
  //     trigger: ".is-style-intro, .is-style-introborder",
  //     start: "top 80%",
  //     toggleActions: "play none none none",
  //   },
  // });

  // *
  // Large CTA animation
  //  large-cta block
  // */

  // function initMultiLayerParallax() {
  //   gsap.utils.toArray(".large-cta").forEach((section) => {
  //     const tl = gsap.timeline({
  //       scrollTrigger: {
  //         trigger: section,
  //         start: "top bottom",
  //         end: "bottom top",
  //         scrub: 1.5,
  //       },
  //     });

  //     // Background image moves slower (classic parallax)
  //     tl.to(
  //       ".large-cta__image",
  //       {
  //         yPercent: -30,
  //         ease: "none",
  //       },
  //       0
  //     );

  //     // Heading moves at medium speed
  //     tl.to(
  //       ".large-cta__heading",
  //       {
  //         yPercent: -20,
  //         ease: "none",
  //       },
  //       0
  //     );

  //     // Content box moves faster (closest to viewer)
  //     tl.to(
  //       ".large-cta__content",
  //       {
  //         yPercent: -40,
  //         ease: "none",
  //       },
  //       0
  //     );
  //   });
  // }

  initRevealAnimation();

  function initRevealAnimation() {
    // Loop through each CTA section
    gsap.utils.toArray(".large-cta").forEach((section, index) => {
      // Find elements within this specific section
      const image = section.querySelector(".large-cta__image");
      const heading = section.querySelector(".large-cta__heading h2");
      const content = section.querySelector(".large-cta__content");

      // Set initial states for elements in this section
      if (image) {
        gsap.set(image, {
          scale: 1.3,
          filter: "blur(10px)",
        });
      }

      if (heading) {
        gsap.set(heading, {
          yPercent: 100,
          opacity: 0,
        });
      }

      if (content) {
        gsap.set(content, {
          yPercent: 50,
          opacity: 0,
          scale: 0.8,
        });
      }

      // Create timeline for this specific section
      const tl = gsap.timeline({
        scrollTrigger: {
          trigger: section, // Use the specific section as trigger
          start: "top 80%",
          end: "center center",
          scrub: 1,
          id: `reveal-${index}`, // Unique ID for debugging
        },
      });

      // Animate elements in this section
      if (image) {
        tl.to(image, {
          scale: 1,
          filter: "blur(0px)",
          duration: 1,
        });
      }

      if (heading) {
        tl.to(
          heading,
          {
            yPercent: 0,
            opacity: 1,
            duration: 0.8,
          },
          0.2
        );
      }

      if (content) {
        tl.to(
          content,
          {
            yPercent: 0,
            opacity: 1,
            scale: 1,
            duration: 0.8,
          },
          0.4
        );
      }
    });
  }

  // stagger fade
  gsap.from(".home-boxes .wp-block-column", {
    opacity: 0,
    y: 30,
    stagger: 0.5,
    duration: 2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: ".home-boxes",
      start: "top 80%",
      toggleActions: "play none none none",
    },
  });

  // Video banner text fade in
  gsap.from(".hero-video__content h2 span", {
    opacity: 0,
    y: 30,
    stagger: 1,
    duration: 3,
    ease: "power2.out",
  });

  // Video banner text scroll up
  gsap.to(".hero-video__content h2", {
    y: -400,
    ease: "none",
    scrollTrigger: {
      trigger: ".hero-video",
      start: "top top",
      end: "bottom top",
      scrub: true,
    },
  });

  const heroBg = document.querySelector(".hero-video.home");
const megaMenu = document.querySelector(".mega-menu");

if (heroBg && megaMenu) {
  // Create a reusable tween
  const fade = gsap.to(heroBg, {
    opacity: 0.3, // dim background (0 = fully hidden, 1 = fully visible)
    duration: 0.4,
    paused: true,
    ease: "power2.out"
  });

  megaMenu.addEventListener("mouseenter", () => fade.play());
  megaMenu.addEventListener("mouseleave", () => fade.reverse());
}


  // *
  // Inner banner title animation
  //  inner panner text
  // */

  //animateInnerBannerTitle();

  function animateInnerBannerTitle() {
    const spans = document.querySelectorAll(".page-title span");

    gsap.to(spans, {
      duration: 0.8,
      opacity: 1,
      x: 0,
      ease: "power2.out",
      stagger: 5,
    });
  }

  // PERFORMANCE TIP: Throttle animations for mobile
  const isMobile = window.innerWidth < 768;
  if (isMobile) {
    // Use simpler animations or disable some effects
    ScrollTrigger.config({
      limitCallbacks: true,
      syncInterval: 150, // Reduce frequency on mobile
    });
  }

  // Refresh ScrollTrigger once everything is set up
  ScrollTrigger.refresh();
});
