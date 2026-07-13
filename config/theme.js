(function () {
    const html = document.documentElement;
    const storedTheme = localStorage.getItem('theme');
    const existingTheme = html.getAttribute('data-theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const useDark = storedTheme === 'dark' || (storedTheme === 'light' ? false : (existingTheme === 'dark' || (existingTheme === null && prefersDark)));

    html.classList.toggle('dark', useDark);
    html.setAttribute('data-theme', useDark ? 'dark' : 'light');
    localStorage.setItem('theme', useDark ? 'dark' : 'light');

    function createThemeToggleButton(isDark) {
        if (document.getElementById('theme-toggle')) return;
        const header = document.getElementById('mainHeader');
        if (!header) return;

        const button = document.createElement('button');
        button.id = 'theme-toggle';
        button.type = 'button';
        button.className = 'inline-flex items-center gap-2 px-3 py-2 rounded-full border border-outline-variant bg-surface-container-low text-on-surface hover:bg-surface-container hover:text-on-surface transition-all duration-200';
        button.setAttribute('aria-label', 'Toggle theme');
        button.innerHTML = '<span class="material-symbols-outlined">' + (isDark ? 'light_mode' : 'dark_mode') + '</span><span class="sr-only">Toggle theme</span>';
        button.addEventListener('click', () => window.toggleTheme());

        header.appendChild(button);
    }

    function updateThemeButton(isDark) {
        const button = document.getElementById('theme-toggle');
        if (!button) return;
        const icon = button.querySelector('.material-symbols-outlined');
        if (!icon) return;
        icon.textContent = isDark ? 'light_mode' : 'dark_mode';
    }

    window.toggleTheme = function () {
        const isDark = html.classList.toggle('dark');
        html.setAttribute('data-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeButton(isDark);
    };

    function initThemeToggle() {
        const initialDark = html.classList.contains('dark');
        createThemeToggleButton(initialDark);
        updateThemeButton(initialDark);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initThemeToggle);
    } else {
        initThemeToggle();
    }
})();
