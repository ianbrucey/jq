const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.vue',
        './app/Http/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('daisyui')
    ],

    // DaisyUI config
    daisyui: {
        themes: [
            {
                light: {
                    ...require("daisyui/src/theming/themes")["light"],
                    primary: "#4F46E5",    // Indigo-600
                    secondary: "#7C3AED",  // Violet-600
                    accent: "#2563EB",     // Blue-600
                    neutral: "#1F2937",    // Gray-800
                    "base-100": "#FFFFFF",
                    info: "#3B82F6",       // Blue-500
                    success: "#10B981",    // Emerald-500
                    warning: "#F59E0B",    // Amber-500
                    error: "#EF4444",      // Red-500
                },
                dark: {
                    ...require("daisyui/src/theming/themes")["dark"],
                    primary: "#6366F1",    // Indigo-500
                    secondary: "#8B5CF6",  // Violet-500
                    accent: "#3B82F6",     // Blue-500
                    neutral: "#374151",    // Gray-700
                    "base-100": "#1F2937", // Gray-800
                    info: "#60A5FA",       // Blue-400
                    success: "#34D399",    // Emerald-400
                    warning: "#FBBF24",    // Amber-400
                    error: "#F87171",      // Red-400
                }
            },
        ],
        darkTheme: "dark",
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: false,
    },
};
