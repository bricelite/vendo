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
                fraunces: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                principale: 'var(--couleur-principale)',
                accent: 'var(--couleur-accent)',
                texte: 'var(--couleur-texte)',
                'texte-secondaire': 'var(--couleur-texte-secondaire)',
                fond: 'var(--couleur-fond)',
                'fond-alterne': 'var(--couleur-fond-alterne)',
                succes: 'var(--couleur-succes)',
                alerte: 'var(--couleur-alerte)',
                avertissement: 'var(--couleur-avertissement)',
                noir: '#000000',
            },
        },
    },

    plugins: [forms],
};
