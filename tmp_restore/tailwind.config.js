import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',  
        './resources/js/**/*.vue',   
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['DM Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                /* Use Slate for the dark bluish overall theme */
                gray: colors.slate,
                
                accent: {
                    DEFAULT: '#e8c547',
                    dim:     '#c9a82e',
                    muted:   'rgba(232,197,71,0.12)',
                    ring:    'rgba(232,197,71,0.3)',
                },
                surface: {
                    0: '#0a0a0b',
                    1: '#111113',
                    2: '#18181b',
                    3: '#1f1f23',
                    4: '#27272c',
                    5: '#2e2e35',
                },
                primary: {
                    a0: 'var(--clr-primary-a0)',
                    a10: 'var(--clr-primary-a10)',
                    a20: 'var(--clr-primary-a20)',
                    a30: 'var(--clr-primary-a30)',
                    a40: 'var(--clr-primary-a40)',
                    a50: 'var(--clr-primary-a50)',
                },
                'surface-tonal': {
                    a0: 'var(--clr-surface-tonal-a0)',
                    a10: 'var(--clr-surface-tonal-a10)',
                    a20: 'var(--clr-surface-tonal-a20)',
                    a30: 'var(--clr-surface-tonal-a30)',
                    a40: 'var(--clr-surface-tonal-a40)',
                    a50: 'var(--clr-surface-tonal-a50)',
                },
                ink: {
                    DEFAULT: '#f0f0f2',
                    muted:   '#8b8b9a',
                    faint:   '#4a4a58',
                },
            },
        },
    },

    plugins: [forms],
};