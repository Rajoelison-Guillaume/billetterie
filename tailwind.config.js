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
                sans: ['Inter', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c3d66',
                },
                accent: '#00d9ff',
                dark: '#0a0e27',
                'card-dark': '#0f1535',
            },
            backgroundImage: {
                'gradient-dark': 'linear-gradient(135deg, #0a0e27 0%, #0f1535 100%)',
                'gradient-blue': 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)',
                'gradient-neon': 'linear-gradient(135deg, #00d9ff 0%, #0ea5e9 100%)',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(0, 217, 255, 0.3)',
                'glow-lg': '0 0 40px rgba(0, 217, 255, 0.5)',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
            },
        },
    },

    plugins: [forms],
};
