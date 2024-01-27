/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./dist/**/*.{html,js,php}"],
  theme: {
    extend: {
      backgroundColor: ['hover'],
      textColor: ['hover'],
      backgroundColor: ['active', 'group-hover'],
      textColor: ['active', 'group-hover'],
    },
  },
  plugins: [],
}

