/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
    './node_modules/preline/preline.js',
    // "./node_modules/flowbite/**/*.js",
  ],
  // media: false,
  // purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    extend: {},
  },
  plugins: [
    require('tailwind-scrollbar-hide'),
    require('preline/plugin'),
    // require('flowbite/plugin'),
  ],
}

