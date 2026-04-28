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
const heroFit = document.querySelector('.hero-fit, .hero-fill')

if (hero) {
  const scroll = document.querySelector('[js-scroll]')

  function initRatioHero() {
    const screen = {
      w: window.innerWidth,
      h: window.innerHeight,
    }

    
    if (screen.w/screen.h < 1.45) {
      hero.classList.add('is-vertical')
      if(hero.offsetHeight > window.innerHeight) hero.classList.add('is-bigger')
      else hero.classList.remove('is-bigger')
    } else hero.classList.remove('is-vertical', 'is-bigger')
    
  } 

  if(window.innerWidth < 600) {
    // IS MOBILE
    hero.style.height = `${hero.offsetHeight}px`
    hero.style.minHeight = `${1}px`
  }

  initRatioHero()
  window.addEventListener('resize', initRatioHero)

  if (scroll) {
    function initScroll() {
      const heroRect = hero.getBoundingClientRect()
      if (heroRect.top + heroRect.height < window.innerHeight) {
        scroll.classList.add('is-bottom')
      } else {
        scroll.classList.remove('is-bottom')
      }

    }
    initScroll()

    window.addEventListener('scroll', initScroll)
    window.addEventListener('resize', initScroll)
    scroll.addEventListener('click', () => {
      window.scrollTo({
        left: 0,
        top: hero.nextElementSibling.offsetTop,
        behavior: 'smooth',
      })
    })
  }
}

// if (heroFit) {
//   function initHeroScroll() {
//     const heroRect = heroFit.getBoundingClientRect()
//     const cover = heroFit.querySelector('.hero-cover')
//     if (window.scrollY > document.querySelector('header').offsetHeight) {
//       if (heroRect.top + heroRect.height < window.innerHeight) {
//         cover.classList.add('is-bottom')
//         cover.classList.remove('is-fixed')
//       } else {
//         cover.classList.add('is-fixed')
//         cover.classList.remove('is-bottom')
//       }
//     } else {
//       cover.classList.remove('is-fixed', 'is-bottom')
//     }
//   }

//   function initHeroParallax() {
//     const heroRect = heroFit.getBoundingClientRect()
//     console.log(heroRect.height, window.innerHeight)
//       if (heroRect.height > window.innerHeight) {
//         console.log('HERE')
//         heroFit.querySelector('.hero-cover img').classList.remove('parallax-image')
//         heroFit.querySelector('.hero-cover div').classList.remove('parallax-image-wrapper')
//         // heroFit.querySelector('img').classList.remove('parallax-image')
//       } 
//   }

//   initHeroScroll()
//   initHeroParallax()

//   window.addEventListener('scroll', initHeroScroll)
//   window.addEventListener('resize', initHeroScroll)
//   window.addEventListener('resize', initHeroParallax)

// }