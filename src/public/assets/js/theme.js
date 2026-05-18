(function () {
    const root = document.documentElement;
    const toggle = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    const text = document.getElementById('theme-toggle-text');

    function currentTheme() {
        return root.getAttribute('data-theme') || 'dark';
    }

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        if (icon) {
            icon.textContent = theme === 'dark' ? '☀️' : '🌙';
        }

        if (text) {
            text.textContent = theme === 'dark' ? 'Light' : 'Dark';
        }
    }

    applyTheme(currentTheme());

    if (toggle) {
        toggle.addEventListener('click', function () {
            applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
        });
    }
})();