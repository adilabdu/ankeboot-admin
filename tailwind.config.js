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
            },
            animation: {
                'rotate': 'rotate 2s linear infinite',
                'slide-in': 'slideIn 150ms linear',
                'slide-up': 'slideUp 100ms linear',
                'slide-out': 'slideOut 150ms linear',
                'slide-in-from-left': 'slideInFromLeft 150ms linear',
                'grow-up': 'growUp 300ms ease-out',
                'scale-up': 'scaleUp 75ms ease-out',
                'appear-down': 'appearDown 75ms ease-out',
            },
            keyframes: {
                rotate: {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0%)' }
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0%)' }
                },
                slideOut: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(100%)' }
                },
                slideInFromLeft: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0%)' }
                },
                growUp: {
                    '0%': { transform: 'scaleY(0%)' },
                    '100%': { transform: 'scaleY(100%)' }
                },
                scaleUp: {
                    '0%': { transform: 'scale(95%)', opacity: 1 },
                    '100%': { transform: 'scale(100%)', opacity: 1 }
                },
                appearDown: {
                    '0%': { transform: 'translateY(95%)', opacity: 0 },
                    '100%': { transform: 'translateY(100%)', opacity: 1 }
                }
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
