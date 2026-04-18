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
        'footer-bg': '#191919',
        'paragraph-gray': '#B2B2B2',
        'card-cream': '#F5F1E8',
        'card-gold': '#FFF4D9',
        'card-beige': '#F8F5F0',
        'check-green': '#7CAA6D',
        'divider-gold': '#CEB270',
        'card-label': '#3A3A3A',
        'intro-bg': '#EFEBE3',
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
