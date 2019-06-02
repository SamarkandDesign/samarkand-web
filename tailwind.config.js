const {
  colors: { pink, teal, purple, yellow, ...colors },
} = require('tailwindcss/defaultTheme');

const cssGrid = require('tailwindcss-grid');

const gridConfig = {
  grids: [1, 2, 4],
  gaps: {
    0: '0',
    4: '1rem',
    8: '2rem',
  },
};

module.exports = {
  plugins: [cssGrid(gridConfig)],
  theme: {
    fontFamily: {
      body: 'Muli, sans-serif',
      sans: 'Muli, sans-serif',
      serif: 'Garamond, Cambria, serif',
    },
    colors: {
      ...colors,
      orange: {
        100: '#ebcfc6',
        200: '#deb0a0',
        300: '#d1907a',
        400: '#c47054',
        500: '#ab573b',
        600: '#85432e',
        700: '#5f3021',
        800: '#391d14',
        900: '#130a07',
      },
    },
  },
};
