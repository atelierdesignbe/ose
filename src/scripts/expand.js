const expand = document.querySelectorAll('[js-expand]')

if (expand.length > 0) {
  let resizeTimer

  function expandClose(elem, button) {
    const items = elem.querySelectorAll('[js-expand-item]')
    items.forEach(item => item.style.transitionDelay = ``)
  
    elem.classList.remove('is-open')
    button.classList.add('disabled')
    const overflow = elem.querySelector('[js-expand-overflow]')
    if (overflow) overflow.classList.add('is-opened')
  
    setTimeout(() => {
      elem.style.display = ''
      button.classList.remove('disabled', 'is-open')
      if (overflow) overflow.classList.remove('is-opened')
    }, 300)
  
    // Retire le clickoutside si mobile
    document.removeEventListener('click', elem._outsideHandler)
    elem._outsideHandler = null
  }
  
  function expandOpen(elem, button) {
    elem.style.display = 'flex'
    button.classList.add('is-open', 'disabled')
    // const items = elem.querySelectorAll('[js-expand-item]')
    const overflow = elem.querySelector('[js-expand-overflow]')
    if (overflow) overflow.classList.add('is-opened')
    
    setTimeout(() => {
      elem.classList.add('is-open')
      // items.forEach((item, index) => item.style.transitionDelay = `${(index * 50) + 100}ms`)
    }, 10)
  
    setTimeout(() => {
      button.classList.remove('disabled')
    }, 300)
  
    setTimeout(() => {
      if (overflow) overflow.classList.remove('is-opened')
    }, 900)
  
    setTimeout(() => {
      if (!elem._outsideHandler) {
        elem._outsideHandler = function (e) {
          if (!elem.contains(e.target) && !button.contains(e.target)) {
            expandClose(elem, button)
          }
        }
        document.addEventListener('click', elem._outsideHandler)
      }
    }, 10);
  }
  
  function expandMobileClick(elem, button) {
    if (button.classList.contains('is-open')) expandClose(elem, button)
    else expandOpen(elem, button)
  }
  
  function resetExpand() {
    for (const elem of expand) {
      const button = elem.parentElement.querySelector('[js-expand-button]')
      if (!button) continue
  
      button.classList.remove('is-open')
      elem.classList.remove('is-open')
  
      // MOBILE
      if (button._mobileHandler) {
        button.removeEventListener('click', button._mobileHandler)
        button._mobileHandler = null
      }
  
      // DESKTOP
      if (button._handlerTarget && button._handler) {
        button._handlerTarget.removeEventListener('mouseover', button._handler)
        button._handler = null
      }
      if (button._closeOnLeaveTarget && button._closeOnLeave) {
        button._closeOnLeaveTarget.removeEventListener('mouseleave', button._closeOnLeave)
        button._closeOnLeave = null
      }
  
      // Nettoyage des références
      button._handlerTarget = null
      button._closeOnLeaveTarget = null
    }
  }
  
  function initExpand() {
    for (const elem of expand) {
      const device = elem.getAttribute('js-expand')
      const button = elem.parentElement.querySelector('[js-expand-button]')

      if (!button) continue
  
      const parent = button.parentElement
  
      // -------------------- MOBILE --------------------
      if (window.innerWidth < 600) {
        const mobileHandler = (e) => {
          e.preventDefault()
          expandMobileClick(elem, button)
          return false
        }
        button._mobileHandler = mobileHandler
        button.addEventListener('click', mobileHandler)
        continue
      }
  
      // -------------------- DESKTOP --------------------
      if (device !== 'mobile') {
  
        const handler = () => {
          if (!button.classList.contains('is-open')) {
            expandOpen(elem, button)
          }
        }
  
        const closeOnLeave = (e) => {
          if (!elem.contains(e.relatedTarget) && !button.contains(e.relatedTarget)) {
            expandClose(elem, button)
          }
        }
  
        // STOCKER LA CIBLE DU LISTENER 👇
        button._handler = handler
        button._handlerTarget = parent
  
        button._closeOnLeave = closeOnLeave
        button._closeOnLeaveTarget = parent
  
        parent.addEventListener('mouseover', handler)
        parent.addEventListener('mouseleave', closeOnLeave)
      }
    }
  }

  // ---------------- INIT + RESET ----------------
  initExpand()
  
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer)
    resizeTimer = setTimeout(() => {
      resetExpand()
      initExpand()
    }, 200)
  })
}