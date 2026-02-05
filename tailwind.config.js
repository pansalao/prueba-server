import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        './vendor/wire-elements/modal/src/ModalComponent.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sogat: ['Verdana', 'sans-serif'],
            },
            colors: {
                sogat: {
                    blue: '#0072C6',
                    'blue-light': '#E0ECF8',
                    'dark-gray': '#31353E',
                    'medium-gray': '#42464E',
                    'border-gray': '#DADADA',
                    'gray-bg': '#F2F2F2',
                    'teal-light': '#C3E0E4',
                    'teal-dark': '#8FC4CB',
                    red: '#A00',
                }
            }
        },
    },

    plugins: [forms],
};
