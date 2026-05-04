/**
 * menu.js
 *
 * Behaviour:
 *  – Dropdown mode (desktop ≥ 600px) : CSS hover/focus-within handles open state
 *                                       JS manages aria-expanded + sibling closing
 *  – Accordion mode                  : data-menu-mode="accordeon" at any viewport
 *  – Accordion mode (mobile < 600px) : data-menu-mode="dropdown" also uses accordion
 *                                       behaviour because the SCSS mixin is applied
 *  – Nav panel (mobile)              : .menu__button-control toggles .menu__nav
 *                                       Lenis scroll is stopped/started accordingly
 *                                       .menu__nav must carry data-lenis-prevent
 *  – Global : click outside → close all  |  Escape → close all + return focus
 *             resize cross 600px → close all open accordions + close nav panel
 */

( function () {
  'use strict';

  // Keep in sync with the @media (max-width: 599px) breakpoint in menu.scss
  const MOBILE_BREAKPOINT = 599;

  // Duration (ms) to wait before hiding the nav after removing is-open.
  // Must be ≥ the longest CSS transition on .menu__nav / .menu__nav-inner.
  const NAV_CLOSE_DELAY = 400;

  let accordeonTimeout = null;
  let navTimeout       = null;

  // ── Helpers ────────────────────────────────────────────────────────────────

  /**
   * Returns the sibling .menu__dropdown right after `trigger`.
   * @param {HTMLElement} trigger
   * @returns {HTMLElement|null}
   */
  function getDropdown( trigger ) {
    const next = trigger.nextElementSibling;
    return next && next.classList.contains( 'menu__dropdown' ) ? next : null;
  }

  // ── Accordion detection ──────────────────────────────────────────────────

  /**
   * Returns true when `dropdown` should use accordion behaviour :
   *  – always when data-menu-mode="accordeon"
   *  – on mobile (≤ MOBILE_BREAKPOINT) when data-menu-mode="dropdown",
   *    because the SCSS mixin applies the accordion styles at that breakpoint.
   * @param {HTMLElement} dropdown
   * @returns {boolean}
   */
  function isAccordion( dropdown ) {
    if ( dropdown.closest( '[data-menu-mode="accordeon"]' ) ) return true;
    if ( window.innerWidth <= MOBILE_BREAKPOINT && dropdown.closest( '[data-menu-mode="dropdown"]' ) ) return true;
    if ( dropdown.getAttribute( 'data-depth-mode' ) === 'accordeon' ) return true;
    return false;
  }

  /**
   * Opens an accordion dropdown.
   * Sets display:block first, then adds is-open after a short delay
   * so the CSS scaleY transition has a painted starting frame.
   * @param {HTMLElement} dropdown
   */
  function accordionOpen( dropdown ) {
    clearTimeout( accordeonTimeout );

    dropdown.style.display = 'block';

    accordeonTimeout = setTimeout( () => {
      dropdown.classList.add( 'is-open' );
    }, 100 );
  }

  /**
   * Closes an accordion dropdown.
   * Removes is-open immediately (CSS transition fires), then hides
   * the element after the transition duration has elapsed.
   * @param {HTMLElement} dropdown
   */
  function accordionClose( dropdown ) {
    clearTimeout( accordeonTimeout );

    dropdown.classList.remove( 'is-open' );

    accordeonTimeout = setTimeout( () => {
      dropdown.style.display = '';
    }, 600 );
  }

  // ── Open / close ──────────────────────────────────────────────────────────

  function open( trigger ) {
    const dropdown = getDropdown( trigger );
    if ( ! dropdown ) return;

    trigger.setAttribute( 'aria-expanded', 'true' );
    dropdown.setAttribute( 'aria-hidden', 'false' );

    if ( isAccordion( dropdown ) ) {
      accordionOpen( dropdown );
    }
    // Dropdown desktop: ouverture gérée par CSS :hover / :focus-within,
    // aria-expanded suffit pour l'accessibilité.
  }

  function close( trigger ) {
    const dropdown = getDropdown( trigger );
    if ( ! dropdown ) return;

    trigger.setAttribute( 'aria-expanded', 'false' );
    dropdown.setAttribute( 'aria-hidden', 'true' );

    if ( isAccordion( dropdown ) ) {
      // Fermer récursivement les sous-niveaux avant d'animer le parent
      dropdown.querySelectorAll( '.menu__trigger[aria-expanded="true"]' ).forEach( close );
      accordionClose( dropdown );
    } else {
      dropdown.classList.remove( 'is-open' );
      dropdown.querySelectorAll( '.menu__trigger[aria-expanded="true"]' ).forEach( close );
    }
  }

  /**
   * Closes all open triggers inside a given root, except the one provided.
   * @param {HTMLElement} root
   * @param {HTMLElement|null} except
   */
  function closeSiblings( root, except = null ) {
    const directTriggers = root.querySelectorAll(
      ':scope > .menu__item > .menu__trigger[aria-expanded="true"]'
    );
    directTriggers.forEach( t => {
      if ( t !== except ) close( t );
    } );
  }

  function closeAll( except = null ) {
    document
      .querySelectorAll( '.menu__trigger[aria-expanded="true"]' )
      .forEach( t => { if ( t !== except ) close( t ); } );
  }

  // ── Lenis helpers ─────────────────────────────────────────────────────────

  // Safe wrappers — no-op if Lenis isn't initialised yet or isn't used.
  // overflow:hidden sur le body bloque aussi le scroll natif du navigateur
  // (lenis.stop() seul ne suffit pas sur iOS/mobile).
  function lenisStop() {
    if ( window.lenis ) window.lenis.stop();
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
  }
  function lenisStart() {
    if ( window.lenis ) window.lenis.start();
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
  }

  // ── Nav panel (mobile burger) ──────────────────────────────────────────────

  /**
   * Initialises the mobile nav panel for a given .menu root.
   *
   * Open sequence :
   *  1. is-open on .menu__button-control  (button style)
   *  2. display:block on .menu__nav       (panel in the flow)
   *  3. timeout 10ms → is-open on .menu__nav  (triggers CSS animation on inner)
   *  4. lenis.stop()                      (lock body scroll)
   *
   * Close sequence :
   *  1. is-open removed from button + nav (CSS reverse animation fires)
   *  2. lenis.start()                     (restore body scroll)
   *  3. closeAll()                        (collapse any open submenus)
   *  4. timeout NAV_CLOSE_DELAY → display:'' (hide after animation)
   *
   * Note: .menu__nav must have data-lenis-prevent so Lenis ignores scroll
   * events inside the panel while it is open.
   *
   * @param {HTMLElement} menu
   */
  function initButtonControl( menu ) {
    const btn        = menu.querySelector( '.menu__button-control' );
    const nav        = menu.querySelector( '.menu__nav' );
    const siteHeader = document.querySelector( '[js-header]' );

    if ( ! btn || ! nav ) return;

    btn.addEventListener( 'click', () => {
      const isOpen = btn.classList.contains( 'is-open' );

      clearTimeout( navTimeout );

      if ( ! isOpen ) {
        // ── Open ──────────────────────────────────────────────────────────
        btn.classList.add( 'is-open' );
        nav.style.display = 'block';
        siteHeader?.classList.add( 'is-open' );

        navTimeout = setTimeout( () => {
          nav.classList.add( 'is-open' );
        }, 10 );

        lenisStop();

      } else {
        // ── Close ─────────────────────────────────────────────────────────
        btn.classList.remove( 'is-open' );
        nav.classList.remove( 'is-open' );
        siteHeader?.classList.remove( 'is-open' );

        lenisStart();
        closeAll();

        navTimeout = setTimeout( () => {
          nav.style.display = '';
        }, NAV_CLOSE_DELAY );
      }
    } );
  }

  // ── Toggle ────────────────────────────────────────────────────────────────

  function toggle( trigger ) {
    const isOpen     = trigger.getAttribute( 'aria-expanded' ) === 'true';
    const parentList = trigger.closest( '.menu__list' );

    if ( ! isOpen ) {
      if ( parentList ) closeSiblings( parentList, trigger );
      open( trigger );
    } else {
      close( trigger );
    }
  }

  // ── Init ──────────────────────────────────────────────────────────────────

  function init() {
    document.querySelectorAll( '.menu' ).forEach( menu => {

      initButtonControl( menu );

      menu.querySelectorAll( '.menu__trigger' ).forEach( trigger => {
        trigger.addEventListener( 'click', e => {
          e.stopPropagation();
          toggle( trigger );
        } );
      } );

    } );

    // ── Global listeners ────────────────────────────────────────────────────

    // Close on click outside
    document.addEventListener( 'click', e => {
      if ( ! e.target.closest( '.menu' ) ) closeAll();
    } );

    // Close on Escape — return focus to the trigger that was active
    document.addEventListener( 'keydown', e => {
      if ( e.key !== 'Escape' ) return;

      const openTrigger = document.querySelector( '.menu__trigger[aria-expanded="true"]' );
      closeAll();

      if ( openTrigger ) openTrigger.focus();
    } );

    // Close all when crossing the mobile ↔ desktop breakpoint.
    // Prevents a dropdown opened as accordion on mobile from staying
    // open (and visually broken) after resizing to desktop.
    // Also resets any open nav panel and restores Lenis.
    let wasMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    window.addEventListener( 'resize', () => {
      const nowMobile = window.innerWidth <= MOBILE_BREAKPOINT;
      if ( wasMobile !== nowMobile ) {
        wasMobile = nowMobile;
        closeAll();

        if ( ! nowMobile ) {
          // Switched to desktop — force-close any open mobile nav panels
          clearTimeout( navTimeout );
          document.querySelectorAll( '.menu' ).forEach( menu => {
            const btn = menu.querySelector( '.menu__button-control' );
            const nav = menu.querySelector( '.menu__nav' );
            if ( btn ) btn.classList.remove( 'is-open' );
            if ( nav ) { nav.classList.remove( 'is-open' ); nav.style.display = ''; }
          } );
          document.querySelector( '[js-header]' )?.classList.remove( 'is-open' );
          lenisStart();
        }
      }
    } );
  }

  // Bootstrap
  if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', init );
  } else {
    init();
  }

} )();
