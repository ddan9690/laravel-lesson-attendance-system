// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'school-green': '#1E7D3D',
        'school-accent': '#28A745',
        'bg-light': '#F9FAFB',
        'text-dark': '#111827',
        'card': '#FFFFFF',
      },
    },
  },
  plugins: [],
}
