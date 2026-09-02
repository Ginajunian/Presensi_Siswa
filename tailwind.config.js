import defaultTheme from 'tailwindcss/default-theme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#F0F9F4',
                    100: '#DCF2E3',
                    200: '#BAE5C8',
                    300: '#8CD1A4',
                    400: '#5AB87D',
                    500: '#379A5E',
                    600: '#297C49',
                    700: '#22633B',
                    800: '#1D4F31',
                    900: '#19422A',
                },
            },
        },
    },

    plugins: [forms],
};