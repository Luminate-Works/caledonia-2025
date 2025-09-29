// Mobile menu + submenu
// ------------------------------
document.addEventListener("DOMContentLoaded", function () {
  var navExpander = document.getElementById("nav-expander");
  var body = document.body;
  var header = document.querySelector("header");
  var sitenavLinks = document.querySelectorAll(".sitenav a");
  var subToggles = document.querySelectorAll(
    ".sitenav .menu-item-has-children > .sub-toggle"
  );

  navExpander.style.cursor = "pointer";
  navExpander.addEventListener("click", function (e) {
    e.preventDefault();
    body.classList.toggle("nav-expanded");
    navExpander.classList.toggle("is-active");
    header.classList.toggle("menu-open");
  });

  sitenavLinks.forEach(function (link) {
    link.addEventListener("click", function () {
      body.classList.remove("nav-expanded");
      navExpander.classList.remove("is-active");
      header.classList.remove("menu-open");
    });
  });

  subToggles.forEach(function (toggle) {
    toggle.addEventListener("click", function () {
      var subMenu = this.nextElementSibling;
      this.classList.toggle("open");
      if (subMenu.style.maxHeight) {
        subMenu.style.maxHeight = null;
      } else {
        subMenu.style.maxHeight = subMenu.scrollHeight + "px";
      }
    });
  });
});

// Sticky header
// ------------------------------
const headerElement = document.querySelector(".header");
const stickyHeader = () => {
  headerElement.classList.toggle(
    "sticky",
    document.documentElement.scrollTop > 0
  );
};
document.addEventListener("scroll", stickyHeader);

// Scroll to top button
// ------------------------------
const backToTopButton = document.querySelector(".b2t");

if (backToTopButton) {
  const toggleBackToTopButton = () => {
    if (window.scrollY > 200) {
      backToTopButton.classList.add("active");
    } else {
      backToTopButton.classList.remove("active");
    }
  };

  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };

  window.addEventListener("scroll", toggleBackToTopButton);
  backToTopButton.addEventListener("click", scrollToTop);
}

// ========================
// 2-column submenu layout
// ========================
(function styleSubmenus() {
  const subMenus = document.querySelectorAll("ul.sub-menu");

  subMenus.forEach((subMenu) => {
    const items = subMenu.querySelectorAll("li");
    if (items.length > 7) {
      subMenu.classList.add("col2");
      const midIndex = Math.ceil(items.length / 2);
      if (items[midIndex]) {
        items[midIndex].classList.add("first-in-col2");
      }
    }
  });
})();

// ========================
// Open Mega Menu on click & align child pages
// ========================
document.addEventListener("DOMContentLoaded", function () {
  const menuItems = document.querySelectorAll(".menu > li.menu-item-has-children");
  const MQ = window.matchMedia("(min-width: 1024px)");

  function alignSubMenu(item) {
    if (!MQ.matches) return; // ✅ only on desktop

    const childMenu = item.querySelector(".child-menu");
    const wrap      = childMenu && childMenu.querySelector(".wrap");
    const subCol    = childMenu && childMenu.querySelector(".sub-menu");
    const link      = item.querySelector(":scope > a");
    if (!childMenu || !wrap || !subCol || !link) return;

    // Temporarily reveal if hidden to measure
    let restore = false;
    if (window.getComputedStyle(childMenu).display === "none" || childMenu.offsetParent === null) {
      restore = true;
      childMenu.style.visibility = "hidden";
      childMenu.style.display = "block";
      childMenu.style.pointerEvents = "none";
    }

    const linkRect = link.getBoundingClientRect();
    const wrapRect = wrap.getBoundingClientRect();
    const subW     = subCol.getBoundingClientRect().width;

    let offset = Math.round(linkRect.left - wrapRect.left);
    const maxOffset = Math.max(0, Math.round(wrapRect.width - subW));
    offset = Math.min(Math.max(offset, 0), maxOffset); // clamp

    subCol.style.transform = `translateX(${offset}px)`;

    if (restore) {
      childMenu.style.display = "";
      childMenu.style.visibility = "";
      childMenu.style.pointerEvents = "";
    }
  }

  menuItems.forEach((item) => {
    const link = item.querySelector(":scope > a");
    const childMenu = item.querySelector(".child-menu");

    if (childMenu) {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        // Close others
        menuItems.forEach((i) => {
          if (i !== item) i.classList.remove("open");
        });

        // Toggle current
        item.classList.toggle("open");

        // Align if now open
        if (item.classList.contains("open")) {
          alignSubMenu(item);
        }
      });
    }
  });

  // Close if clicked outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".menu")) {
      menuItems.forEach((i) => i.classList.remove("open"));
    }
  });

  // Re-align on resize if a menu is open and still desktop
  window.addEventListener("resize", function () {
    if (!MQ.matches) {
      document.querySelectorAll(".sub-menu.col2").forEach(el => el.style.transform = "");
      return;
    }
    const openItem = document.querySelector(".menu > li.menu-item-has-children.open");
    if (openItem) alignSubMenu(openItem);
  });
});



// event listener for scroll
// ------------------------------
// document.addEventListener("scroll", () => {
// 	stickyHeader();
//     scrollToTop();
// });

// Align full width
// ------------------------------
// const alignFull = () => {
//     const viewportFullWidth = document.querySelectorAll(".alignfull");

//     if (!viewportFullWidth) return;

//     viewportFullWidth.forEach((item) => {
//       var rect = item.parentElement.getBoundingClientRect();
//       item.style.marginLeft = -rect.left + "px";
//       item.style.width = document.body.clientWidth + "px";
//     });
// };

// document.addEventListener("DOMContentLoaded", () => {
//     alignFull();
// });

// window.addEventListener("resize", () => {
//     alignFull();
// });

// ===============================================
// wrap swipe table
// ===============================================
function wrapSwipeTables() {
  var figures = document.querySelectorAll(".wp-block-table.is-style-swipe");

  figures.forEach(function (figure) {
    var table = figure.querySelector("table");

    if (table) {
      var wrapper = document.createElement("div");
      wrapper.classList.add("wp-block-table-wrap");
      table.parentNode.insertBefore(wrapper, table);
      wrapper.appendChild(table);
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  wrapSwipeTables();
});

// ===============================================
// page animations
// ===============================================
document.addEventListener("DOMContentLoaded", () => {
  const fadeIns = document.querySelectorAll(
    ".fade, .fade-in-up, .fade-in, .zoom-in, .zoom-in-sm, .fade-in-left, .fade-in-right, .wp-block-cover, .wp-block-columns"
  );

  const appearOptions = {
    threshold: 0.015,
  };

  const appearOnScroll = new IntersectionObserver(function (
    entries,
    appearOnScroll
  ) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("in-view");
        appearOnScroll.unobserve(entry.target);
      }
    });
  },
  appearOptions);

  fadeIns.forEach((fade) => {
    appearOnScroll.observe(fade);
  });
});

// ===============================================
// equal block heights
// ===============================================
function equalizeRowHeights(className, breakpoint) {
  var rows = document.getElementsByClassName(className);
  var maxHeight = 0;

  // small screens
  if (window.innerWidth < breakpoint) {
    for (var i = 0; i < rows.length; i++) {
      rows[i].style.height = "auto";
    }
    return;
  }

  // set heights
  for (var i = 0; i < rows.length; i++) {
    rows[i].style.height = "auto";
    maxHeight = Math.max(maxHeight, rows[i].clientHeight);
  }
  for (var i = 0; i < rows.length; i++) {
    rows[i].style.height = maxHeight + "px";
  }
}

// breakpoints
const classBreakpoints = [
  { className: "equal", breakpoint: 680 },
  { className: "e1", breakpoint: 782 },
  { className: "e2", breakpoint: 782 },
  { className: "e3", breakpoint: 782 },
  { className: "sh", breakpoint: 300 },
];

// apply equal heights
function applyEqualHeights() {
  classBreakpoints.forEach((item) =>
    equalizeRowHeights(item.className, item.breakpoint)
  );
}

window.addEventListener("load", applyEqualHeights);
window.addEventListener("resize", applyEqualHeights);

// ===============================================
// anchor links scroll behaviour
// ===============================================
document.addEventListener("DOMContentLoaded", function () {
  const hash = window.location.hash;

  if (hash) {
    window.location.hash = "";

    setTimeout(function () {
      const targetElement = document.querySelector(hash);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: "smooth" });
      }
    }, 500);
  }
});
