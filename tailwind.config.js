/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './blocks/**/*.php',
    './blocks-render/**/*.php',
    './template-parts/**/*.php',
    './block-assets/**/*.css',
    './resources/**/*.css',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          'light-gold': '#FCE5B0',
          gold: '#B89352',
          'dark-brown': '#6B4502',
        },
      },
    },
  },
  plugins: [],
};
