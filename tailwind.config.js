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
                'chef-black': '#0B0B0B',
                'chef-gold': '#D4AF37',
                'chef-gold-light': '#E5C158',
                'chef-gold-dark': '#B8941F',
                'chef-gold-hover': '#F4D675',
                'chef-gold-focus': '#FFE5A3',
                'chef-gray': '#1A1A1A',
                'chef-gray-light': '#2A2A2A',
                'chef-gray-dark': '#0F0F0F',
                'chef-gray-lighter': '#3A3A3A',
                'chef-gray-darker': '#050505',
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'soft-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'gold': '0 4px 6px -1px rgba(212, 175, 55, 0.1), 0 2px 4px -1px rgba(212, 175, 55, 0.06)',
                'gold-glow': '0 0 20px rgba(212, 175, 55, 0.15)',
                'card': '0 8px 32px rgba(0, 0, 0, 0.3), 0 4px 16px rgba(0, 0, 0, 0.2)',
                'card-hover': '0 12px 40px rgba(0, 0, 0, 0.4), 0 6px 20px rgba(0, 0, 0, 0.3)',
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-gentle': 'bounce 2s infinite',
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'scale-in': 'scaleIn 0.2s ease-out',
                'float': 'float 3s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            }
        },
    },

    plugins: [forms],
};
