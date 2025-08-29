
document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".content-panel");
    const navLinks = document.querySelectorAll(".contents a");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // remove active from all links
                    navLinks.forEach((link) => link.classList.remove("active"));
                    // add active to the one that matches current section
                    const id = entry.target.getAttribute("id");
                    const activeLink = document.querySelector(`.contents a[href="#${id}"]`);
                    if (activeLink) activeLink.classList.add("active");
                }
            });
        },
        {
            rootMargin: "-40% 0px -50% 0px", // triggers when section is near middle of viewport
            threshold: 0
        }
    );

    sections.forEach((section) => observer.observe(section));
});