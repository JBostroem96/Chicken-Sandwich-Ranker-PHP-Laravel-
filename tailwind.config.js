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
            //add custom font(s)
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                oswald: ['Oswald', ...defaultTheme.fontFamily.sans],
            },
            //add custom color
            colors: {
                brandOrange: '#ff6300', 
            },
            borderRadius: {
                'xl': '1rem', 
            },
        },
    },

    plugins: [forms],
};
