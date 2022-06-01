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
      './resources/views/**/**/*.blade.php',
      './resources/assets/js/components/**/*.vue',
      './resources/assets/js/**/*.vue',
      './modules/Cloud/Resources/views/**/**/*.blade.php',
      './vendor/akaunting/laravel-menu/src/Presenters/Admin/Tailwind.php',
      './safelist.txt'
    ],

    safelist: [
      {
        pattern: /.*/,
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
                'status': {
                  'success': '#F1F6EE',
                  'danger':  '#fae6e6',
                  'sent':    '#FEF5E7',
                  'viewed':  '#EEEEF3',
                  'draft':   '#ECECEC',
                  'partial': '#E6F1F6',
                  'canceled': '#282828',
                  'warning': '#FEF5E7'
                },
                'text-status': {
                  'success': '#63914A',
                  'danger':  '#B80000',
                  'sent':    '#DD8E0A',
                  'viewed':  '#4D4F7D',
                  'draft':   '#3B3B3B',
                  'partial': '#006395',
                  'canceled': '#ffffff',
                  'warning': '#b87708'
                },
                'body': {
                  DEFAULT: '#fcfcfc'
                },
                'light-gray': '#C7C9D9',
                'dark-blue': '#15284B',
                'lighter-gray': '#F2F2F5',
                'purple-lighter': '#F2F4FC',
                'modal-background': 'rgba(0, 0, 0, 0.3)'
          },

          spacing: {
              'modal': '610px',
              '5.5':   '1.30rem', 
              '9.5':   '2.45rem',
              '12.5':  '3.2rem',
              '31':    '30.938rem',
              '32.5':  '8.5rem',
              '33':    '8.5rem',
              '37':    '9.25rem',
              '46':    '46.875rem',
          },

          margin: {
            '10.5':   '2.6rem',
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
          },

          animation: {
              vibrate: 'vibrate 2s cubic-bezier(.36, .07, .19, .97) infinite;',
              pulsate_transparent: 'pulsate_transparent 1500ms ease infinite;',
              pulsate: 'pulsate 1500ms ease infinite;',
              spin: 'spin 1000ms infinite',
              submit: 'submit 0.7s ease alternate infinite'
          },

          transitionProperty: {
            'height': 'height',
            'spacing': 'margin, padding',
            'visible': 'visible, opacity'
          }
        },

        appearance: ['hover', 'focus'],
        container: {
            center: true,
        },
    },

    variants: {
      transitionProperty: ['responsive', 'motion-safe', 'motion-reduce'],
      float: ['responsive', 'direction'],
      margin: ['responsive', 'direction'],
      padding: ['responsive', 'direction'],
      inset: ['responsive', 'direction'],
      textAlign: ['responsive', 'direction'],
      space: ['responsive', 'direction'],
      rotate: ['responsive', 'direction'],
      
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