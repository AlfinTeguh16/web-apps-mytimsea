import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                background: "var(--background)",
                foreground: "var(--foreground)",
        
                'blue-ribbon': {
                    DEFAULT: '#005CFF',
                    50: '#B8D1FF',
                    100: '#A3C4FF',
                    200: '#7AAAFF',
                    300: '#5290FF',
                    400: '#2976FF',
                    500: '#005CFF',
                    600: '#0048C7',
                    700: '#00348F',
                    800: '#001F57',
                    900: '#000B1F',
                    950: '#000103',
                },
                'cerulean': {
                    DEFAULT: '#06A7D5',
                    50: '#97E5FC',
                    100: '#83E1FB',
                    200: '#5BD7FA',
                    300: '#33CDF9',
                    400: '#0CC4F8',
                    500: '#06A7D5',
                    600: '#047C9E',
                    700: '#035168',
                    800: '#012731',
                    900: '#000000',
                    950: '#000000',
                },
                'text-color-navy':{
                  DEFAULT: '#001F56'
                }
              },
        },
    },
    plugins: [],
};
