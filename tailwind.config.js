/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'templates/**/*.html.twig',
    'assets/js/**/*.js'
  ],
  safelist: [
    'italic',
    'border-l-4',
    'border-green-500',
    'my-8',
    'pl-8',
    'md:pl-12',
    'py-6',
    'text-2xl',
    'md:text-3xl',
    'mb-5',
    'py-3',
    'max-w-5xl'
  ],
  theme: {
    extend: {},
    fontFamily: {
      'raleway': ['raleway'],
    }
  },
  plugins: [],
}
