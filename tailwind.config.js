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
            /* 🅰️ Font Family: Plus Jakarta Sans + Source Serif 4 */
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Source Serif 4"', ...defaultTheme.fontFamily.serif],
            },

            /* 🎨 CPAC Theme 2.0 – Refined Color Palette */
            colors: {
                'primary-navy': '#002147',     // Brand blue for headers, cards, buttons
                'secondary-navy': '#003366',   // Hover, sidebar, links
                'accent-gold': '#E8B100',      // Pending highlights
                'success-green': '#2E8B57',    // Approved reservations
                'error-red': '#D9534F',        // Rejected reservations
                'quick-blue': '#004C99',       // Quick action buttons
                'violet-report': '#6A5ACD',    // Reports or analytics
                'bg-white': '#F9FAFB',         // General background
                'border-gray': '#DDE2E6',      // Card borders / shadows
            },

            /* 🪶 Subtle box shadows and transitions for UI smoothness */
            boxShadow: {
                soft: '0 4px 12px rgba(0, 0, 0, 0.05)',
                glow: '0 0 10px rgba(0, 33, 71, 0.2)',
            },

            /* 🌈 Optional Gradient for headers or banners */
            backgroundImage: {
                'navy-gradient': 'linear-gradient(90deg, #002147, #003366)',
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/line-clamp'),
    ],

    /* ✅ Safelist dynamic Tailwind classes (used from backend logic) */
    safelist: [
        'border-blue-500',
        'border-green-500',
        'border-yellow-500',
        'border-red-500',
        'border-gray-500',
        'text-blue-600',
        'text-green-600',
        'text-yellow-600',
        'text-red-600',
        'text-gray-600',
        'bg-blue-100',
        'bg-green-100',
        'bg-yellow-100',
        'bg-red-100',
        'bg-gray-100',
    ],
};
