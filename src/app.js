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

  initRatioHero()
  window.addEventListener('resize', initRatioHero)
}

const publications = document.querySelectorAll('.publication')
if (publications.length > 0) {
  publications.forEach(el => {
    el.addEventListener('mouseenter', () => {
      el.classList.remove('is-leaving')
      el.classList.add('is-entering')
    })

    el.addEventListener('mouseleave', () => {
      el.classList.remove('is-entering')
      el.classList.add('is-leaving')
    })
  })
}