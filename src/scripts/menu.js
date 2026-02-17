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