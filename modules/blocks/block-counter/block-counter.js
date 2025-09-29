document.addEventListener("DOMContentLoaded", () => {
  // 	const initCounters = () => {
  // 		const numberContainers = document.querySelectorAll(".statistics__number");
  // 		numberContainers.forEach((container) => {
  // 			const text = container.textContent;
  // 			container.innerHTML = "";
  // 			// Create a span for each character and append to container
  // 			text.split("").forEach(char => {
  // 				const span = document.createElement("span");
  // 				span.textContent = char;
  // 				container.appendChild(span);
  // 			});
  // 			const spans = container.querySelectorAll("span");
  // 			gsap.fromTo(
  // 				spans,
  // 				{ autoAlpha: 0, x: "-20px" }, // Start state: hidden and slightly left
  // 				{
  // 					autoAlpha: 1,
  // 					x: "0px", // End state: visible and in place
  // 					stagger: 0.05,
  // 					duration: 0.5,
  // 					scrollTrigger: {
  // 						trigger: container,
  // 						start: "top 90%",
  // 						end: "bottom 60%",
  // 						once: true, // Ensure the animation only triggers once
  // 					},
  // 				}
  // 			);
  // 		});
  // 	};
  // 	initCounters();
  // });
  // document.addEventListener("DOMContentLoaded", function() {
  //     // Select all elements with the .statistics__number class
  //     const numbers = document.querySelectorAll(".statistics__number");
  //     numbers.forEach(number => {
  //       // Split each number into digits
  //       const digits = number.textContent.split("");
  //       number.innerHTML = "";
  //       let numberSpan = document.createElement("span");
  //       numberSpan.style.display = "inline-block";
  //       digits.forEach(digit => {
  //         let digitSpan = document.createElement("span");
  //         digitSpan.textContent = digit;
  //         numberSpan.appendChild(digitSpan);
  //       });
  //       number.appendChild(numberSpan);
  //     });
  //     // GSAP animation
  //     gsap.to(".statistics__number span span", {
  //       scrollTrigger: {
  //         trigger: ".statistics__number",
  //         start: "top 80%",
  //       },
  //       opacity: 1,
  //       x: 0,
  //       stagger: 0.075,
  //       duration: 0.75,
  //       ease: "power1.out",
  //     });
});
