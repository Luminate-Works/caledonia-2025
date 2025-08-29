document.addEventListener('DOMContentLoaded', function() {
    // Initialize scrolling text animations
    const scrollingContainers = document.querySelectorAll('.scrolling-text');
    
    scrollingContainers.forEach(container => {
        const ul = container.querySelector('ul');
        const items = container.querySelectorAll('li');
        
        if (!ul || items.length === 0) return;
        
        // Clone items for seamless loop
        items.forEach(item => {
            const clone = item.cloneNode(true);
            ul.appendChild(clone);
        });
        
        // Get all items including clones
        const allItems = ul.querySelectorAll('li');
        const itemHeight = items[0].offsetHeight;
        const totalHeight = itemHeight * items.length;
        
        // Set initial positions and create timeline
        const tl = gsap.timeline({ repeat: -1, ease: "none" });
        
        // Animate the container upward
        tl.to(ul, {
            y: -totalHeight,
            duration: items.length * 2, // 2 seconds per item
            ease: "none"
        });
        
        // Function to update opacity based on position
        function updateOpacity() {
            const containerRect = container.getBoundingClientRect();
            const containerCenter = containerRect.top + containerRect.height / 2;
            
            allItems.forEach(item => {
                const itemRect = item.getBoundingClientRect();
                const itemCenter = itemRect.top + itemRect.height / 2;
                const distance = Math.abs(containerCenter - itemCenter);
                const maxDistance = containerRect.height / 2;
                
                // Calculate opacity: 1 at center, 0.3 at edges
                let opacity = 1 - (distance / maxDistance) * 0.7;
                opacity = Math.max(0.3, Math.min(1, opacity));
                
                gsap.set(item, { opacity: opacity });
            });
        }
        
        // Update opacity on scroll and continuously during animation
        gsap.ticker.add(updateOpacity);
        
        // Initial opacity setup
        updateOpacity();
        
        // Optional: Pause on hover
        container.addEventListener('mouseenter', () => {
            tl.pause();
        });
        
        container.addEventListener('mouseleave', () => {
            tl.play();
        });
    });
});