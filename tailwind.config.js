import defaultTheme from 'tailwindcss/defaultTheme';
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
            colors: {
                brand: {
                    dark: 'var(--bg-main)',
                    surface: 'var(--bg-surface)',
                    primary: '#B33232',
                    whatsapp: '#25D366',
                    income: '#10B981',
                    expense: '#B33232',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [forms],
};

