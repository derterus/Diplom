/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./views/**/*.php",
      "./templates/**/*.php",
      "./src/**/*.js",
  ],
  theme: {
      extend: {
          colors: {
              'primary-color': 'var(--primary-color)',
              'primary-dark': 'var(--primary-dark)',
              'accent-color': 'var(--accent-color)',
              'text-color': 'var(--text-color)',
              'bg-color': 'var(--bg-color)',
              'card-bg': 'var(--card-bg)',
          },
          fontFamily: {
              'main': ['Montserrat', 'sans-serif'],
              'secondary': ['Open Sans', 'sans-serif'],
          },
      },
  },
  plugins: [],
}