document.addEventListener("DOMContentLoaded", () => {
gsap.utils.toArray('.alternative-box').forEach((box) => {
  let tl = gsap.timeline({
    scrollTrigger: {
      trigger: box,
      start: "top 85%",
    }
  });

  tl.from(box.querySelector('.numbers'), { x: -50, opacity: 0, duration: 0.5 })
    .from(box.querySelector('.alt-box-image'), { scale: 0.8, opacity: 0, duration: 0.6, ease: "back.out(1.7)" }, "-=0.3")
    .from(box.querySelector('.content'), { y: 30, opacity: 0, duration: 0.6 }, "-=0.2");
});

});
