/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/views/app.blade.php",
        "./resources/js/**/*.{vue,js}",
    ],
    theme: {
        extend: {
            fontFamily: {
                'basic': ['"Inter"']
            },
            colors: {
                'wallpaper': '#F3F4F6',
                'brand': {
                    'primary': '#FF8100',
                    'secondary': '#FF810020',
                },
                'border': {
                    'light': '#D5D5D7',
                    'dark': '#D7DBE0',
                },
                'subtitle': '#6A727F',
            },
            fontSize: {
                'xs': '0.75rem',
                '2xs': '0.7rem',
            }
        },
        screens: {
            '2xl': {'max': '1535px'},
            // => @media (max-width: 1535px) { ... }

            'xl': {'max': '1279px'},
            // => @media (max-width: 1279px) { ... }

            'lg': {'max': '1023px'},
            // => @media (max-width: 1023px) { ... }

            'md': {'max': '767px'},
            // => @media (max-width: 767px) { ... }

            'sm': {'max': '639px'},
            // => @media (max-width: 639px) { ... }

            'xs': {'max': '474px'},
            // => @media (max-width: 474px) { ... }
        },
    },
    plugins: [],
}
