
const hero   = document.querySelector('.hero');
// const header = document.querySelector('[js-header]');

// ── CSS var --header-height ───────────────────────────────────────────────────
// Utilisée par .hero-bar (top: var(--header-height)) pour démarrer sous le header
// if (header) {
//   const setHeaderHeight = () =>
//     document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`);
//   setHeaderHeight();
//   window.addEventListener('resize', setHeaderHeight);
// }

if (hero) {
  const isFullsize = hero.classList.contains('hero-fullsize');

  // ── Header is-light : hero fullsize avec cover (pas sur la home) ────────────
  // const isHome = hero.classList.contains('hero-home');
  // if (header && isFullsize) {
  //   header.classList.add('is-light');
  // }

  // ── Ratio / vertical ──────────────────────────────────────────────────────
  function initRatioHero() {
    // const isMobile = window.innerWidth < 600;

    // Reset minHeight avant mesure pour ne pas biaiser le calcul
    hero.style.minHeight = '';

    const isBelow     = hero.classList.contains('hero-below');
    const heroContent = isBelow ? hero.querySelector('.hero-content') : hero;

    const screen = {
      w: window.innerWidth,
      h: window.innerHeight,
    };

    if (screen.w / screen.h < 1.45) {
      hero.classList.add('is-vertical');
      if (heroContent.offsetHeight > window.innerHeight) hero.classList.add('is-bigger');
      else hero.classList.remove('is-bigger');
    } else {
      if (heroContent.offsetHeight > window.innerHeight) hero.classList.add('is-bigger');
      else hero.classList.remove('is-bigger');
      hero.classList.remove('is-vertical');
    }

    // ── Mobile fullsize : min-height = viewport réel (iOS-safe) ──────────────
    // min-height → fluide si le contenu dépasse, pas de height fixe
    // if (isMobile && isFullsize) {
    //   hero.style.minHeight = `${window.innerHeight}px`;
    // }

    // if (hero.classList.contains('hero-fill')) {
    //   if(!isMobile &&hero.classList.contains('is-bigger')) {
    //     const contentH = hero.querySelector('.hero-content').offsetHeight
    //     hero.querySelector('.hero-cover-wrap').style.height = `${contentH > window.innerHeight ? window.innerHeight : contentH}px`
    //   } else {
    //     hero.querySelector('.hero-cover-wrap').style = ``
    //   }
    // }
  }

  initRatioHero();
  window.addEventListener('resize', initRatioHero);

}
