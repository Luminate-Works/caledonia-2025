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
      "#sidebar, #content .wp-block-column, #content .wp-block-cover, .fade, .hero"
    ),
    {
      initial: { opacity: 0 },
      onEnter: { opacity: 1, duration: 1, ease: "power2.out" },
    }
  );

  // General fade-in-up page animations
  createScrollTriggerAnimation(
    document.querySelectorAll(
      ".fade-in-up, #content blockquote, #content h2, #content h3, #content h4, #content h5, #content h6, #content p, #content ul:not(.calendar-links), #content .wp-block-buttons"
    ),
    {
      initial: { opacity: 0, y: 10 }, // start 30px below
      onEnter: { opacity: 1, y: 0, duration: 1, ease: "power2.out" }, // fade in and move up
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
  // check if any target element has the .has-white-color class
  const isWhite = document.querySelector(
    ".is-style-intro.has-white-color strong, .is-style-introborder.has-white-color strong"
  );

  let split = SplitText.create(
    ".is-style-intro strong, .is-style-introborder strong",
    { type: "words, chars" }
  );

  // choose the color based on the presence of the class
  const startColor = isWhite ? "#ffffff66" : "#01003566";

  gsap.from(split.chars, {
    duration: 1,
    color: startColor,
    stagger: 0.05,
    ease: "power2.out",
    scrollTrigger: {
      trigger: ".is-style-intro, .is-style-introborder",
      start: "top 80%",
      toggleActions: "play none none none",
    },
  });

  // *
  // Large CTA animation
  //  large-cta block
  // */

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

  gsap.to(".banner-slider-overlay", {
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
      ease: "power2.out",
    });

    megaMenu.addEventListener("mouseenter", () => fade.play());
    megaMenu.addEventListener("mouseleave", () => fade.reverse());
  }

  // *
  // Inner banner title animation
  //  inner panner text
  // */

  animateInnerBannerTitle();

  function animateInnerBannerTitle() {
    const spans = document.querySelectorAll(".page-title span");

    if (!spans.length) return;

    gsap.from(spans, {
      opacity: 0,
      y: 30, // start 30px lower and move up
      stagger: 1, // delay between each span
      duration: 3, // slow fade-up
      ease: "power2.out",
      scrollTrigger: {
        trigger: ".page-title",
        start: "top 80%", // when the title enters viewport
        toggleActions: "play none none none",
      },
    });
  }

  // Curtain
  const links = document.querySelectorAll(
    "a:not(#wpadminbar a):not(.menu-item-has-children > a)"
  );
  const mm = gsap.matchMedia();

  // Curtain animation only on screens above 782px
  mm.add("(min-width: 782px)", () => {
    const curtainOverlay = document.querySelector(".curtain-overlay");
    const curtain1 = document.querySelector(".curtain-1");
    const curtain2 = document.querySelector(".curtain-2");

    // Initial hidden state to avoid flashing on load
    curtainOverlay.classList.add("curtain-hidden");

    // Set starting position (bottom of screen)
    gsap.set([curtain1, curtain2], { y: "100%" });

    // --- timeline (no permanent onComplete) ---
    const curtainTimeline = gsap.timeline({ paused: true });
    curtainTimeline
      .to(curtain1, {
        y: "0%",
        duration: 0.8,
        ease: "power2.inOut",
        onStart: () => {
          curtainOverlay.classList.remove("curtain-hidden");
          curtainOverlay.style.zIndex = 9999;
        },
      })
      .to(
        curtainOverlay,
        {
          opacity: 1,
          duration: 0.4,
          ease: "power1.out",
        },
        "-=0.4"
      );

    // Play curtain and then navigate (one-time callback)
    function playCurtainAndNavigate(href) {
      curtainTimeline.eventCallback("onComplete", () => {
        curtainOverlay.classList.add("curtain-hidden");
        curtainOverlay.style.zIndex = -1;
        curtainTimeline.eventCallback("onComplete", null); // clear callback
        window.location.href = href;
      });
      curtainTimeline.restart(true);
    }

    // --- improved link handling ---
    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        if (e.defaultPrevented) return;
        if (e.button && e.button !== 0) return; // only left click
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return; // ignore modifier clicks
        if (this.target && this.target !== "_self") return; // new tab/window
        if (this.hasAttribute("download")) return;

        const hrefAttr = this.getAttribute("href");
        if (!hrefAttr) return;

        let url;
        try {
          url = new URL(hrefAttr, window.location.href);
        } catch {
          return; // malformed href, let browser handle
        }

        // Skip any link that contains a hash (anchor) – same page or cross page
        if (url.hash) return;

        // Only animate same-origin internal links without hashes
        if (url.origin !== location.origin) return;

        e.preventDefault();
        playCurtainAndNavigate(url.href);
      });
    });

    // Reset curtains on back/forward navigation
    window.addEventListener("popstate", () => {
      curtainOverlay.classList.add("curtain-hidden");
      curtainOverlay.style.zIndex = -1;
      curtainOverlay.style.opacity = 0;
      curtain1.style.transform = "translateY(100%)";
      curtain2.style.transform = "translateY(100%)";

      // Optional: reload if you need a full reset
      // setTimeout(() => {
      //   window.location.reload();
      // }, 10);
    });

    // Cleanup for screens below 782px
    return () => {
      links.forEach((link) => {
        const newLink = link.cloneNode(true);
        link.parentNode.replaceChild(newLink, link);
      });
    };
  });

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
