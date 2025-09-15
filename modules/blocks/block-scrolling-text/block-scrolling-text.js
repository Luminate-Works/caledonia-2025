document.addEventListener("DOMContentLoaded", function () {
  const select = (e) => document.querySelector(e);
  const selectAll = (e) => document.querySelectorAll(e);

  // Get elements from your original markup
  const ulContainer = select(".half ul");
  const originalItems = selectAll(".half ul li");
  const containerHeight = select(".half:last-child").clientHeight;

  // Clone items above and below for seamless loop
  function setupInfiniteScroll() {
    const itemHeight = 80; // Approximate height per item
    const clonesNeeded = Math.ceil(containerHeight / itemHeight) + 2;

    // Store original items data
    const originalData = Array.from(originalItems).map((item) => ({
      text: item.innerHTML,
      classes: item.className,
    }));

    // Clone items above (for upward scrolling)
    for (let i = 0; i < clonesNeeded; i++) {
      const clone = document.createElement("li");
      const sourceIndex = i % originalData.length;
      clone.innerHTML = originalData[sourceIndex].text;
      clone.className = originalData[sourceIndex].classes + " clone clone-top";
      ulContainer.insertBefore(clone, ulContainer.firstChild);
    }

    // Clone items below (for downward scrolling)
    for (let i = 0; i < clonesNeeded; i++) {
      const clone = document.createElement("li");
      const sourceIndex = i % originalData.length;
      clone.innerHTML = originalData[sourceIndex].text;
      clone.className =
        originalData[sourceIndex].classes + " clone clone-bottom";
      ulContainer.appendChild(clone);
    }

    return {
      itemHeight,
      clonesNeeded,
      originalCount: originalData.length,
    };
  }

  // Setup the infinite scroll structure
  const scrollConfig = setupInfiniteScroll();

  // Get all items including clones (like your recipe approach)
  const allItems = selectAll(".half ul li");
  const totalItems = allItems.length;

  // Position items initially
  allItems.forEach((item, i) => {
    gsap.set(item, {
      y:
        i * scrollConfig.itemHeight -
        scrollConfig.clonesNeeded * scrollConfig.itemHeight,
      opacity: 0.3,
    });
  });

  // Create the auto-scroll animation (similar to your ScrollTrigger approach)
  const tl = gsap.timeline({ repeat: -1, ease: "none" });

  function createAutoScroll() {
    const scrollDistance = scrollConfig.originalCount * scrollConfig.itemHeight;
    const duration = scrollConfig.originalCount * 2; // 2 seconds per original item

    // Animate all items like your recipe.forEach approach
    allItems.forEach((item, i) => {
      gsap.to(
        item,
        {
          duration: duration,
          y: `-=${scrollDistance}`,
          ease: "none",
          repeat: -1,
          modifiers: {
            y: function (y, target) {
              const currentY = parseFloat(y);
              const containerCenter = containerHeight / 2;
              const itemCenter = currentY + scrollConfig.itemHeight / 2;

              // Calculate distance from center for opacity (like your recipe opacity logic)
              const distanceFromCenter = Math.abs(itemCenter - containerCenter);
              let opacity;

              if (distanceFromCenter < 20) {
                opacity = 1;
                target.classList.add("center");
              } else {
                target.classList.remove("center");
                if (distanceFromCenter < 60) opacity = 0.8;
                else if (distanceFromCenter < 100) opacity = 0.6;
                else if (distanceFromCenter < 140) opacity = 0.4;
                else if (distanceFromCenter < 180) opacity = 0.2;
                else opacity = 0;
              }

              gsap.set(target, { opacity: opacity });

              // Reset position when item goes too far up (seamless loop)
              if (currentY < -scrollDistance - scrollConfig.itemHeight * 2) {
                return (
                  currentY +
                  scrollConfig.originalCount * scrollConfig.itemHeight +
                  "px"
                );
              }

              return currentY + "px";
            },
          },
        },
        i * 0.1
      ); // Slight stagger like your approach
    });
  }

  // Initialize the animation
  createAutoScroll();

});
