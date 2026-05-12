document.addEventListener('DOMContentLoaded', () => {
  const burgerBtn = document.getElementById('burgerBtn');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  const navItems = document.querySelectorAll('.sidebar .nav-item');

  function closeSidebar() {
    document.body.classList.remove('sidebar-open');
    if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'false');
  }

  function openSidebar() {
    document.body.classList.add('sidebar-open');
    if (burgerBtn) burgerBtn.setAttribute('aria-expanded', 'true');
  }

  function toggleSidebar() {
    if (document.body.classList.contains('sidebar-open')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  }

  if (burgerBtn) {
    burgerBtn.addEventListener('click', toggleSidebar);
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }

  navItems.forEach((item) => {
    item.addEventListener('click', () => {
      if (window.innerWidth <= 1024) {
        closeSidebar();
      }
    });
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) {
      closeSidebar();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeSidebar();
    }
  });

  // Attendance widget
  const grid = document.getElementById('attGrid');
  if (grid && !grid.dataset.loaded) {
    const statuses = [
      'present','present','present','present','absent','late','present',
      'empty','present','present','late','present','present','empty',
      'empty','present','absent','present','present','present','empty',
      'empty','present','present','present','absent','late','present',
      'present','present','empty'
    ];

    statuses.forEach((s, i) => {
      const d = document.createElement('div');
      d.className = 'att-cell ' + s;
      d.textContent = s !== 'empty' ? (i + 1) : '';
      grid.appendChild(d);
    });

    grid.dataset.loaded = '1';
  }
});