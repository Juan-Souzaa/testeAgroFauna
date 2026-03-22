import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                headline: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                folio: {
                    primary: '#00236f',
                    'primary-container': '#1e3a8a',
                    'primary-fixed': '#dce1ff',
                    surface: '#f7f9fb',
                    'surface-low': '#f2f4f6',
                    'surface-high': '#e6e8ea',
                    'surface-highest': '#e0e3e5',
                    'surface-bright': '#f7f9fb',
                    'on-surface': '#191c1e',
                    'on-surface-variant': '#444651',
                    secondary: '#515f74',
                    'secondary-container': '#d5e3fc',
                    'on-secondary-container': '#57657a',
                    outline: '#757682',
                    'outline-variant': '#c5c5d3',
                    'on-primary': '#ffffff',
                    error: '#ba1a1a',
                    'error-container': '#ffdad6',
                    'tertiary-container': '#384055',
                    'on-tertiary-fixed': '#131b2e',
                    'on-secondary-fixed-variant': '#3a485b',
                },
            },
            boxShadow: {
                folio: '0 10px 40px -10px rgba(25, 28, 30, 0.08)',
            },
        },
    },

    plugins: [forms],
};
