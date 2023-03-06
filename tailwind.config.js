const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php', './storage/framework/views/*.php', './resources/views/**/*.blade.php', './resources/js/**/*.vue'],
  darkMode: 'media',
  theme: {
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      red: colors.red,
      blue: colors.blue,
      orange: colors.orange,
      green: colors.green,
      gray: colors.gray,
      zinc: colors.zinc,
      yellow: {
        100: '#fef3c7',
        300: '#fbe36a',
        400: '#cea915',
        500: '#ca9a09',
        600: '#ab790e',
        800: '#92622b',
        900: '#3e240d',
      },
    },
    extend: {
      borderColor: (theme) => ({
        DEFAULT: theme('colors.gray.200', 'currentColor'),
      }),
      boxShadow: (theme) => ({
        outline: '0 0 0 2px ' + theme('colors.indigo.500'),
      }),
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
      fill: (theme) => theme('colors'),
    },
  },
  variants: {
    extend: {
      fill: ['focus', 'group-hover'],
    },
  },
  plugins: [require('@tailwindcss/forms')],
}
