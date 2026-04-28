// Search modal
const searchModal = document.querySelector('[js-search-modal]')
const searchOpenBtns = document.querySelectorAll('[js-search-open]')
const searchCloseBtn = document.querySelector('[js-search-close]')
const searchInput = searchModal ? searchModal.querySelector('input[type="search"]') : null

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
