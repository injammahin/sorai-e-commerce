import defaultTheme from 'tailwindcss/defaultTheme';

export default {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  theme: {
    extend: {
      colors: {
        bone: '#faf8f5', ink: '#121212', linen: '#f0ebe3', hairline: '#ddd7ce',
        copper: {300:'#dca07c', 500:'#c4622f', 600:'#a84f27'},
        forest: '#173d32', wine: '#642738'
      },
      fontFamily: {sans:['Inter', ...defaultTheme.fontFamily.sans], display:['Cormorant Garamond','Georgia','serif']},
      maxWidth: {'8xl':'90rem'},
      boxShadow: {panel:'0 24px 60px rgba(18,18,18,.12)'},
      transitionTimingFunction: {editorial:'cubic-bezier(.22,1,.36,1)'},
      letterSpacing: {widest2:'.18em', widest3:'.28em'}
    }
  },
  plugins: []
};
