// ── Submenus (accordéon mobile / aria desktop) ────────────────────────────────

document.querySelectorAll('.menu__trigger').forEach( trigger => {
  trigger.addEventListener( 'click', () => {
    const dropdown = trigger.nextElementSibling;
    if ( ! dropdown || ! dropdown.classList.contains( 'menu__dropdown' ) ) return;

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
});

// ── Ferme les submenus au click en dehors ─────────────────────────────────────
document.addEventListener( 'click', e => {
  if ( ! e.target.closest( '.menu__item' ) ) {
    document.querySelectorAll( '.menu__trigger[aria-expanded="true"]' ).forEach( t => {
      t.setAttribute( 'aria-expanded', 'false' );
      const d = t.nextElementSibling;
      if ( d ) d.classList.remove( 'is-open' );
    });
  }
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