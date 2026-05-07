// ── Submenus ──────────────────────────────────────────────────────────────────
// Mobile  (< 600px) : toggle au click (accordéon)
// Desktop (≥ 600px) : CSS :hover gère l'ouverture visuelle,
//                     mouseenter/mouseleave synchronisent aria-expanded

const MENU_BREAKPOINT = 600;

function closeAllTriggers() {
  document.querySelectorAll( '.menu__trigger[aria-expanded="true"]' ).forEach( t => {
    t.setAttribute( 'aria-expanded', 'false' );
    const d = t.nextElementSibling;
    if ( d ) d.classList.remove( 'is-open' );
  });
}

document.querySelectorAll('.menu__trigger').forEach( trigger => {
  const dropdown = trigger.nextElementSibling;
  if ( ! dropdown || ! dropdown.classList.contains( 'menu__dropdown' ) ) return;

  // ── Click : mobile uniquement ──────────────────────────────────────────
  trigger.addEventListener( 'click', () => {
    if ( window.innerWidth >= MENU_BREAKPOINT ) {
      trigger.blur(); // retire le focus pour éviter tout effet :focus-within résiduel
      return;
    }

    const isOpen = trigger.getAttribute( 'aria-expanded' ) === 'true';

    // Ferme les autres au même niveau
    const parentList = trigger.closest( '.menu__list' );
    if ( parentList ) {
      parentList.querySelectorAll( ':scope > .menu__item > .menu__trigger[aria-expanded="true"]' ).forEach( other => {
        if ( other === trigger ) return;
        other.setAttribute( 'aria-expanded', 'false' );
        const sib = other.nextElementSibling;
        if ( sib ) sib.classList.remove( 'is-open' );
      });
    }

    if ( isOpen ) {
      trigger.setAttribute( 'aria-expanded', 'false' );
      dropdown.classList.remove( 'is-open' );
    } else {
      trigger.setAttribute( 'aria-expanded', 'true' );
      dropdown.classList.add( 'is-open' );
    }
  });

  // ── Hover : desktop uniquement (sync aria-expanded avec CSS :hover) ────
  const item = trigger.parentElement; // .menu__item
  if ( item ) {
    item.addEventListener( 'mouseenter', () => {
      if ( window.innerWidth < MENU_BREAKPOINT ) return;
      trigger.setAttribute( 'aria-expanded', 'true' );
    });
    item.addEventListener( 'mouseleave', () => {
      if ( window.innerWidth < MENU_BREAKPOINT ) return;
      trigger.setAttribute( 'aria-expanded', 'false' );
    });
  }
});

// ── Ferme au click en dehors (desktop) ────────────────────────────────────────
document.addEventListener( 'click', e => {
  if ( window.innerWidth < MENU_BREAKPOINT ) return;
  if ( ! e.target.closest( '.menu__item' ) ) closeAllTriggers();
});

// ── Burger menu ───────────────────────────────────────────────────────────────

const menu = document.querySelector('[js-menu]')
const menuWrapper = menu.querySelector('.menu-wrapper')
const menuButton = document.querySelector('[js-menu-button]')
const header = document.querySelector('.header')
let isOpen = false

if (menuButton) {
  menuButton.addEventListener('click', () => {
    isOpen = !isOpen
    menu.classList.add('is-disabled')

    if (isOpen) {
      // OPEN
      menu.style.display = 'flex'

      if(menuWrapper.scrollHeight < menu.offsetHeight * .9) menuWrapper.classList.add('is-center')

      setTimeout(() => {
        menu.classList.add('is-open')
        menuButton.classList.add('is-open')
        header.classList.add('menu-open')

        window.lenis.stop()
        document.body.classList.add('overflow-hidden');
        document.documentElement.classList.add('overflow-hidden');
      }, 20)

      setTimeout(() => {
        menu.classList.remove('is-disabled')
      }, 600)
      
    } else {
      // close
      menu.classList.remove('is-open')
      menuButton.classList.remove('is-open')
      header.classList.remove('menu-open')

      setTimeout(() => {
        menu.style.display = ''
        menu.classList.remove('is-disabled')

        window.lenis.start()
        document.body.classList.remove('overflow-hidden');
        document.documentElement.classList.remove('overflow-hidden');
      }, 400)
    }
  
  })
}