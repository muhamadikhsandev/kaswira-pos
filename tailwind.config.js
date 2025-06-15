/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // Menambahkan dukungan dark mode berbasis kelas
  content: [
    "./resources/**/*.blade.php",  // Untuk file Blade Laravel
    "./resources/**/*.js",         // File JavaScript
    "./resources/**/*.vue",        // File Vue
    "./resources/**/*.css",        // Kalau ada file CSS juga
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
