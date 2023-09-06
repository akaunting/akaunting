module.exports = {
  purge: [
    './resources/**/*.html',
    './resources/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms'),
  ],
}
