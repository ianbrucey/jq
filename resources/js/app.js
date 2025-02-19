import './bootstrap';

// Theme handling
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        current: localStorage.getItem('theme') || 'light',

        init() {
            document.documentElement.setAttribute('data-theme', this.current);
        },

        setTheme(theme) {
            this.current = theme;
            localStorage.setItem('theme', theme);
            document.documentElement.setAttribute('data-theme', theme);
        }
    });

    // Initialize theme
    Alpine.store('theme').init();
});
