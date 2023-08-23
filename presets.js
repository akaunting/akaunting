const defaultTheme = require('tailwindcss/defaultTheme')

const fontFamily = defaultTheme.fontFamily;

fontFamily['sans'] = [
  'Quicksand', // <-- Quicksand is default sans font now
  'system-ui',
  // <-- Can provide more font fallbacks here
];

module.exports = {

    content: [
        './resources/views/**/*.blade.php',
        './resources/assets/js/**/*.vue',
        './vendor/akaunting/laravel-menu/src/Presenters/Admin/Tailwind.php',
        './safelist.txt'
    ],

    safelist: [
        {
            pattern: /^[^/&]*$/,
        },

        {
            pattern: /^p-/,
            variants: ['ltr', 'rtl'],
        },

        {
            pattern: /^m-/,
            variants: ['ltr', 'rtl'],
        },

        {
            pattern: /^left-/,
            variants: ['ltr', 'rtl'],
        },

        {
            pattern: /^right-/,
            variants: ['ltr', 'rtl'],
        },

        {
            pattern: /^w/,
        },

        {
            pattern: /^h/,
        },

        {
            pattern: /^inset/,
        },

        {
            pattern: /^top/,
        },

        {
            pattern: /^bottom/,
        },

        {
            pattern: /^translate/,
        },
    ],

    darkMode: 'class', // or 'media' or 'class',

    theme: {
        fontFamily: fontFamily,

        extend: {
            fontSize: {
                '2xl': ['1.375rem','1.5rem'], // 22PX
                '3xl': ['1.5rem','1.75rem'], // 24PX
                '4xl': ['1.75rem','2rem'], // 28PX
                '5xl': ['2.25rem','2.5rem'], // 36PX
                '6xl': ['2.5rem','2.75rem'], // 40PX
                '7xl': ['2.75rem', '3rem'], // 44PX
                '8xl': ['3rem', '3.25rem'],
            },

            colors: {
                'green': {
                    DEFAULT: '#6ea152',
                    '50': '#f8faf6', 
                    '100': '#f1f6ee', 
                    '200': '#dbe8d4', 
                    '300': '#c5d9ba', 
                    '400': '#9abd86', 
                    '500': '#6ea152', 
                    '600': '#63914a', 
                    '700': '#53793e', 
                    '800': '#426131', 
                    '900': '#364f28'
                },

                'purple': {
                    DEFAULT: '#55588b',
                    '50': '#f7f7f9', 
                    '100': '#eeeef3', 
                    '200': '#d5d5e2', 
                    '300': '#bbbcd1', 
                    '400': '#888aae', 
                    '500': '#55588b', 
                    '600': '#4d4f7d', 
                    '700': '#404268', 
                    '800': '#333553', 
                    '900': '#2a2b44'
                },

                'red': {
                    DEFAULT: '#cc0000',
                    '50': '#fcf2f2', 
                    '100': '#fae6e6', 
                    '200': '#f2bfbf', 
                    '300': '#eb9999', 
                    '400': '#db4d4d', 
                    '500': '#cc0000', 
                    '600': '#b80000', 
                    '700': '#990000', 
                    '800': '#7a0000', 
                    '900': '#640000'
                },

                'orange': {
                    DEFAULT: '#f59e0b',
                    '50': '#fffaf3', 
                    '100': '#fef5e7', 
                    '200': '#fde7c2', 
                    '300': '#fbd89d', 
                    '400': '#f8bb54', 
                    '500': '#f59e0b', 
                    '600': '#dd8e0a', 
                    '700': '#b87708', 
                    '800': '#935f07', 
                    '900': '#784d05'
                },

                'blue': {
                    DEFAULT: '#006ea6',
                    '50': '#f2f8fb', 
                    '100': '#e6f1f6', 
                    '200': '#bfdbe9', 
                    '300': '#99c5db', 
                    '400': '#4d9ac1', 
                    '500': '#006ea6', 
                    '600': '#006395', 
                    '700': '#00537d', 
                    '800': '#004264', 
                    '900': '#003651'
                },

                'black': {
                    DEFAULT: '#424242',
                    '50': '#f6f6f6', 
                    '100': '#ececec', 
                    '200': '#d0d0d0', 
                    '300': '#b3b3b3', 
                    '400': '#7b7b7b', 
                    '500': '#424242', 
                    '600': '#3b3b3b', 
                    '700': '#323232', 
                    '800': '#282828', 
                    '900': '#202020'
                },

                'lilac': {
                    DEFAULT: '#F8F9FE',
                    '100':   '#F5F7FA',
                    '300':   '#EDF0FC',
                    '900':   '#DCE2F9'
                },

                'golden': {
                    DEFAULT: '#D1C989',
                    '900': '#BFB882',
                },

                'rose': {
                    DEFAULT: '#f43f5e',
                    '50' : '#fff1f2',
                    '100': '#ffe4e6',
                    '200': '#fecdd3',
                    '300': '#fda4af',
                    '400': '#fb7185',
                    '500': '#f43f5e',
                    '600': '#e11d48',
                    '700': '#be123c',
                    '800': '#9f1239',
                    '900': '#881337'
                },

                'silver': {
                    DEFAULT: '#7F8997',
                    '50': '#F0F1F3', 
                    '100': '#D4D7DC', 
                    '200': '#B8BDC5', 
                    '300': '#9CA3AE', 
                    '400': '#7F8997', 
                    '500': '#636F80', 
                    '600': '#475569', 
                    '700': '#424F61', 
                    '800': '#3A4555', 
                    '900': '#323B49'
                },

                'pastel_green': {
                    DEFAULT: '#E0F1E3',
                    '50': '#E0F1E3', 
                    '100': '#CCE0D0', 
                    '200': '#B8D0BD', 
                    '300': '#A3BFAB', 
                    '400': '#8FAE98', 
                    '500': '#7B9E85', 
                    '600': '#678D72', 
                    '700': '#5E8268', 
                    '800': '#56765F', 
                    '900': '#4D6A55'
                },

                'peach_orange': {
                    DEFAULT: '#FCF2D9',
                    '50': '#FCF2D9', 
                    '100': '#F0E0BE', 
                    '200': '#E5CFA4', 
                    '300': '#DABE89', 
                    '400': '#CEAC6E', 
                    '500': '#C39B54', 
                    '600': '#B78939', 
                    '700': '#AC8035', 
                    '800': '#9C7430', 
                    '900': '#8C692B'
                },

                'wisteria': {
                    DEFAULT: '#E5E4FA',
                    '50': '#E5E4FA', 
                    '100': '#D0CEE8', 
                    '200': '#BAB9D5', 
                    '300': '#A5A4C3', 
                    '400': '#908EB1', 
                    '500': '#7A799E', 
                    '600': '#65638C', 
                    '700': '#5F5D83', 
                    '800': '#565577', 
                    '900': '#4D4C6B'
                },

                'status': {
                    'success':  '#F1F6EE',
                    'danger':   '#fae6e6',
                    'warning':  '#FEF5E7',
                    'info':     '#e6f1f6',
                    'sent':     '#FEF5E7',
                    'viewed':   '#EEEEF3',
                    'draft':    '#ECECEC',
                    'partial':  '#E6F1F6',
                    'canceled': '#282828',
                    'approved': '#F1F6EE',
                    'invoiced': '#CCE0D0',
                    'refused':  '#fae6e6',
                    'expired':  '#fae6e6',
                    'confirmed': '#F1F6EE',
                    'issued':   '#fae6e6',
                    'green':    '#f1f6ee',
                    'purple':   '#eeeef3',
                    'red':      '#fae6e6',
                    'orange':   '#fef5e7',
                    'blue':     '#e6f1f6',
                    'black':    '#ececec',
                    'lilac':    '#F5F7FA',
                    'golden':   '',
                    'rose':     '#ffe4e6',
                    'silver':   '#D4D7DC',
                    'pastel_green': '#CCE0D0',
                    'peach_orange': '#F0E0BE',
                    'wisteria': '#D0CEE8',
                },

                'text-status': {
                    'success':  '#63914A',
                    'danger':   '#B80000',
                    'warning':  '#b87708',
                    'info':     '#006395',
                    'sent':     '#DD8E0A',
                    'viewed':   '#4D4F7D',
                    'draft':    '#3B3B3B',
                    'partial':  '#006395',
                    'canceled': '#ffffff',
                    'approved': '#63914A',
                    'invoiced': '#678D72',
                    'refused':  '#b80000',
                    'expired':  '#B80000',
                    'confirmed': '#63914A',
                    'issued':   '#b80000',
                    'green':    '#63914a',
                    'purple':   '#4d4f7d',
                    'red':      '#b80000',
                    'orange':   '#dd8e0a',
                    'blue':     '#006395',
                    'black':    '#3b3b3b',
                    'lilac':    '',
                    'golden':   '',
                    'rose':     '#e11d48',
                    'silver':   '#475569',
                    'pastel_green': '#678D72',
                    'peach_orange': '#B78939',
                    'wisteria': '#65638C'
                },

                'body': {
                    DEFAULT: '#fcfcfc'
                },

                'light-gray': '#C7C9D9',

                'dark-blue': '#15284B',

                'lighter-gray': '#F2F2F5',

                'purple-lighter': '#F2F4FC',

                'modal-background': 'rgba(0, 0, 0, 0.3)',

                'black-medium': '#424242',

                'red-light': '#FF6B6B',

                'default': '#6ea152',
            },

            spacing: {
                'modal': '610px',
                '500':   '500px',
                '5.5':   '1.30rem', 
                '9.5':   '2.45rem',
                '12.5':  '3.2rem',
                '18':    '4.5rem',
                '31':    '30.938rem',
                '32.5':  '8.5rem',
                '33':    '8.5rem',
                '37':    '9.25rem',
                '46':    '46.875rem',
                '98':    '27.8rem',
            },

            margin: {
                '10.5': '2.6rem',
            },

            keyframes: {
                vibrate: {
                    '0.50%, 10%, 20%, 30%, 40%, 50%': { transform: 'translate3d(0.5px, 0, 0)' },
                    '5%, 15%, 25%, 35%, 45%': { transform: 'translate3d(-0.5px, 0, 0)' },
                    '100%': { transform: 'translate3d(0.5px, 0, 0)' },
                },

                pulsate_transparent: {
                    '0%': { transform: 'scale(0.9, 0.9)' },
                    '50%': { transform: 'scale(1.14, 1.14)' },
                    '100%': { transform: 'scale(0.9, 0.9)' },
                },

                pulsate: {
                    '0%': { transform: 'transform: scale(1, 1)', opacity: '0.05' },
                    '50%': { opacity: '0.25' },
                    '100%': { transform: 'scale(1.5, 1.5)', opacity: '0' },
                },

                spin: {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },

                submit: {
                    '0%': { boxShadow: '0 28px 0 -28px #ffffff' },
                    '100%': { boxShadow: '0 28px 0 #ffffff' },
                },

                submit_second: {
                    '0%': { boxShadow: '0 28px 0 -28px #55588b' },
                    '100%': { boxShadow: '0 28px 0 #55588b' },
                },

                marquee: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-100%)' },
                },

                marquee_long: {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-350%)' },
                },

                hourglass: {
                    '0%': { transform: 'rotate(180deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },

                setting: {
                    '0%': {transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)'},
                }
            },

            animation: {
                vibrate: 'vibrate 2s cubic-bezier(.36, .07, .19, .97) infinite;',
                pulsate_transparent: 'pulsate_transparent 1500ms ease infinite;',
                pulsate: 'pulsate 1500ms ease infinite;',
                spin: 'spin 1000ms infinite',
                submit: 'submit 0.7s ease alternate infinite',
                submit_second: 'submit_second 0.7s ease alternate infinite',
                marquee: 'marquee 9s linear infinite',
                marquee_long: 'marquee_long 14s linear infinite',
                hourglass: 'hourglass 1500ms infinite',
                setting: 'setting 2000ms infinite'
            },

            transitionProperty: {
                'height': 'height',
                'spacing': 'margin, padding',
                'visible': 'visible, opacity',
                'backgroundSize': 'background-size'
            },

            backgroundSize: {
                '0-2': '0 2px',
                'full-2': '100% 2px'
            },
  
            backgroundPosition: {
                '0-full': ' 0 100%'
            },

            minHeight: {
                '500': '500px',
            }
        },

        appearance: ['hover', 'focus'],

        container: {
            center: true,
        },
    },

    variants: {
        transitionProperty: [
            'responsive',
            'motion-safe',
            'motion-reduce'
        ],

        float: [
            'responsive',
            'direction'
        ],

        margin: [
            'responsive',
            'direction'
        ],

        padding: [
            'responsive',
            'direction'
        ],

        inset: [
            'responsive',
            'direction'
        ],

        textAlign: [
            'responsive',
            'direction'
        ],

        space: [
            'responsive',
            'direction'
        ],

        rotate: [
            'responsive',
            'direction'
        ],

        extend: {
            display: ['group-hover'],
            opacity: ['checked', 'disabled'],
            borderColor: ['checked'],
            fontWeight: ['hover'],
            borderRadius: ['responsive', 'hover', 'focus'],
            borderWidth: ['responsive', 'hover', 'focus'],
            translate: ['responsive', 'hover'],
        }
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@themesberg/flowbite/plugin'),
        require('tailwindcss-dir')(),

        function ({ addComponents }) {
            addComponents({
                '.container': {
                    maxWidth: '100%',

                    '@screen sm': {
                        maxWidth: '100%',
                    },

                    '@screen md': {
                        maxWidth: '100%',
                    },

                    '@screen lg': {
                        maxWidth: '1000px',
                    },

                    '@screen xl': {
                        maxWidth: '895px',
                    },

                    '@screen 2xl': {
                        maxWidth: '1145px',
                    },
                }
            })
        }
    ]
};
