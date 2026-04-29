// Search modal
const searchModal = document.querySelector('[js-search-modal]')
const searchOpenBtns = document.querySelectorAll('[js-search-open]')
const searchCloseBtn = document.querySelector('[js-search-close]')
const searchInput = searchModal ? searchModal.querySelector('[js-search-textarea]') : null

function openSearch() {
  if (!searchModal) return
  searchModal.classList.add('is-open')
  document.body.classList.add('overflow-hidden')
  document.documentElement.classList.add('overflow-hidden')
  if (window.lenis) window.lenis.stop()
  setTimeout(() => {
    if (searchInput) searchInput.focus()
  }, 300)
}

function closeSearch() {
  if (!searchModal) return
  searchModal.classList.remove('is-open')
  document.body.classList.remove('overflow-hidden')
  document.documentElement.classList.remove('overflow-hidden')
  if (window.lenis) window.lenis.start()
}

// Auto-resize + Enter submit pour tous les textarea de recherche (modal + page résultats)
function initSearchTextarea(textarea) {
  if (!textarea) return

  // Resize initial si valeur pré-remplie (page résultats)
  textarea.style.height = 'auto'
  textarea.style.height = textarea.scrollHeight + 'px'

  // Auto-resize à la saisie
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto'
    textarea.style.height = textarea.scrollHeight + 'px'
  })

  // Enter soumet le form, Shift+Enter insère un saut de ligne
  textarea.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault()
      const form = textarea.closest('form')
      if (form) form.submit()
    }
  })
}

// Init tous les [js-search-textarea] de la page
document.querySelectorAll('[js-search-textarea]').forEach(initSearchTextarea)

if (searchModal) {
  searchOpenBtns.forEach(btn => {
    btn.addEventListener('click', openSearch)
  })

  if (searchCloseBtn) {
    searchCloseBtn.addEventListener('click', closeSearch)
  }

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && searchModal.classList.contains('is-open')) {
      closeSearch()
    }
  })
}
