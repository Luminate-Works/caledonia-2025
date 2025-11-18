document.addEventListener('DOMContentLoaded', function () {
  const select = document.getElementById('content-select');
  const panels = document.querySelectorAll('.content-panel');
  const mq = window.matchMedia('(max-width: 1023px)'); // run only below 1025px

  function activateMobileDropdown() {
    if (!select) return;

    // Show only the first panel initially
    panels.forEach((panel, index) => {
      panel.style.display = index === 0 ? 'block' : 'none';
    });

    // Change visible panel when dropdown changes
    select.addEventListener('change', function () {
      const targetId = this.value.substring(1); // remove leading #
      panels.forEach(panel => {
        panel.style.display = panel.id === targetId ? 'block' : 'none';
      });
    });
  }

  function deactivateMobileDropdown() {
    // Reset any inline display styles when back to desktop view
    panels.forEach(panel => {
      panel.style.display = '';
    });
  }

  // Initial check
  if (mq.matches) {
    activateMobileDropdown();
  }

  // Listen for viewport changes (in case of resizing)
  mq.addEventListener('change', e => {
    if (e.matches) {
      activateMobileDropdown();
    } else {
      deactivateMobileDropdown();
    }
  });
});
