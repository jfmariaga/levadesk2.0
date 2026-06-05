import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: {
                    DEFAULT: '#1E3A8A',
                    dark: '#172554',
                    light: '#3B82F6',
                },

                accent: {
                    DEFAULT: '#7C3AED',
                },
            },
        },
    },

    plugins: [
        forms,
    ],
}