/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './blocks/**/*.php',
    './blocks/**/*.js',
    './blocks/**/*.jsx',
    './blocks/**/*.css',
    './template-parts/**/*.php',
    './resources/**/*.css',
  ],
  theme: {
    extend: {
      colors: {
        cream: '#F9F5EE',
        'cream-light': '#FFF6E5',
        'gold-light': '#FCE5B0',
        gold: '#B89352',
        'gold-dark': '#6B4502',
        'dark-text': '#25272B',
      },
      fontFamily: {
        sofia: ['"Sofia Sans"', 'sans-serif'],
        poppins: ['"Poppins"', 'sans-serif'],
        inter: ['"Inter"', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
