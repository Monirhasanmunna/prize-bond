import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: false,
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                questrial: ['Questrial', 'sans-serif'],
                gilroy: ['Gilroy', 'Helvetica', 'Arial', 'serif'],
            },
        },

        container: {
            center: true,
            padding: '1rem',
            screens: {
                sm: '540px',
                md: '720px',
                lg: '960px',
                xl: '1140px',
                // Optional: 2xl and beyond
                '2xl': '1320px',
            },
        },
    },

    plugins: [forms],
};
