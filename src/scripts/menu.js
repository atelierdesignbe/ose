const menu = document.querySelector('[js-menu]')
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

      setTimeout(() => {
        menu.classList.add('is-open')
        menuButton.classList.add('is-open')
        header.classList.add('menu-open')
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
      }, 400)
    }
  
  })
}