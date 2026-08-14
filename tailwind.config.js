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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette EPA, échantillonnée depuis logo.jpeg (voir CLAUDE.md)
                epa: {
                    red: '#EE0916', // rouge du chapeau/main du logo (couleur principale)
                    black: '#1A1A1A', // texte "EPA"
                    green: '#02AC09', // segment "e+"
                    purple: '#960191', // segment "e+"
                    magenta: '#C40183', // segment "e+"
                    orange: '#F5860D', // segment "e+"
                    blue: '#025FCB', // segment "e+"
                    gray: '#57585C', // "e+" central
                },
            },
        },
    },

    plugins: [forms],
};
