import './styles/app.scss'

import './scripts/expand'
import './scripts/menu'
import './scripts/ajax'

import Lenis from 'lenis'

// import './styles/tailwind.scss'
console.log('🎨 Atelier Design Theme loaded');

// HMR Vite (pour JS/CSS uniquement)
if (import.meta.hot) {
  import.meta.hot.accept();
}

// Votre code
document.addEventListener('DOMContentLoaded', () => {
  console.log('✅ DOM ready');
});

const lenis = new Lenis({
  autoRaf: true,
});

window.lenis = lenis


const hero = document.querySelector('.hero')

if (hero) {

  function initRatioHero() {
    const screen = {
      w: window.innerWidth,
      h: window.innerHeight,
    }

    
    if (screen.w/screen.h < 1.45) hero.classList.add('is-vertical')
    else hero.classList.remove('is-vertical')
    
  } 

  if(window.innerWidth < 600) {
    // IS MOBILE
    hero.style.height = `${hero.offsetHeight}px`
    hero.style.minHeight = `${1}px`
  }

  initRatioHero()
  window.addEventListener('resize', initRatioHero)
}

