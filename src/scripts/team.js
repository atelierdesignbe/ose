// Team page — filtrage JS par taxonomie member_type + load more (12 par 12)
const teamFilter = document.querySelector('[js-team-filter]')

if (teamFilter) {
  const filterBtns   = teamFilter.querySelectorAll('[js-team-filter-btn]')
  const memberItems  = [...teamFilter.querySelectorAll('[js-team-member]')]
  const labelEl      = teamFilter.querySelector('[js-team-filter-label]')
  const titleEl      = teamFilter.querySelector('[js-team-title]')
  const expandBtn    = teamFilter.querySelector('[js-expand-button]')
  const expandEl     = teamFilter.querySelector('[js-expand]')
  const loadMoreBtn  = teamFilter.querySelector('[js-team-load-more]')
  const loadMoreWrap = teamFilter.querySelector('[js-team-load-more-wrapper]')

  const PER_PAGE = 12
  let currentFilter = 'all'
  let currentPage   = 1

  // ── Helpers ───────────────────────────────────────────────────────────────
  function getItemTypes(item) {
    try { return JSON.parse(item.dataset.types || '[]') } catch { return [] }
  }

  function getFilteredItems(slug) {
    return memberItems.filter(item => {
      const types = getItemTypes(item)
      return slug === 'all' || types.includes(slug)
    })
  }

  function updateLoadMore(filtered, page) {
    if (!loadMoreWrap) return
    loadMoreWrap.style.display = filtered.length > page * PER_PAGE ? '' : 'none'
  }

  // ── Filtrage avec animation ───────────────────────────────────────────────
  // Appelé sur changement de filtre — réinitialise la pagination à 1
  function filterMembers(slug) {
    currentFilter = slug
    currentPage   = 1

    // Fade-out des items visibles
    memberItems.forEach(item => {
      if (item.style.display !== 'none') {
        item.style.opacity   = '0'
        item.style.transform = 'translateY(10px)'
      }
    })

    setTimeout(() => {
      const filtered  = getFilteredItems(slug)
      const showCount = PER_PAGE

      // Show / hide
      memberItems.forEach(item => {
        const idx = filtered.indexOf(item)
        item.style.display = (idx !== -1 && idx < showCount) ? '' : 'none'
      })

      // Staggered fade-in
      filtered.slice(0, showCount).forEach((item, i) => {
        setTimeout(() => {
          item.style.opacity   = '1'
          item.style.transform = 'translateY(0)'
        }, i * 40)
      })

      updateLoadMore(filtered, 1)
    }, 220)
  }

  // ── Load more ─────────────────────────────────────────────────────────────
  // Révèle les 12 items suivants sans toucher aux items déjà affichés
  function loadMore() {
    currentPage++
    const filtered = getFilteredItems(currentFilter)
    const from     = (currentPage - 1) * PER_PAGE
    const toShow   = filtered.slice(from, currentPage * PER_PAGE)

    toShow.forEach(item => {
      item.style.opacity   = '0'
      item.style.transform = 'translateY(10px)'
      item.style.display   = ''
    })

    toShow.forEach((item, i) => {
      setTimeout(() => {
        item.style.opacity   = '1'
        item.style.transform = 'translateY(0)'
      }, i * 40)
    })

    updateLoadMore(filtered, currentPage)
  }

  // ── État actif des boutons ────────────────────────────────────────────────
  function setActiveBtn(slug) {
    filterBtns.forEach(btn => {
      btn.classList.toggle('is-active', btn.dataset.filter === slug)
    })
    if (expandBtn) {
      expandBtn.classList.toggle('is-filtered', slug !== 'all')
    }
  }

  // ── Fermeture du dropdown ─────────────────────────────────────────────────
  function closeDropdown() {
    if (!expandEl || !expandBtn) return
    expandEl.classList.remove('is-open')
    expandBtn.classList.remove('is-open')
    setTimeout(() => { expandEl.style.display = '' }, 300)
  }

  // ── URL history ───────────────────────────────────────────────────────────
  function pushFilterUrl(slug) {
    const base = window.location.pathname
      .replace(/\/$/, '')
      .replace(/\/[a-z0-9-]+$/, '')
      .replace(/\/team\/[a-z0-9-]*$/, '/team')

    const url = slug === 'all'
      ? base + '/'
      : base + '/' + slug + '/'

    history.pushState({ teamFilter: slug }, '', url)
  }

  // ── Click sur un filtre ───────────────────────────────────────────────────
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const slug  = btn.dataset.filter
      const label = btn.dataset.label || btn.textContent.trim()
      const title = btn.dataset.title || btn.textContent.trim()

      if (labelEl) labelEl.textContent = label
      if (titleEl) titleEl.textContent = title

      setActiveBtn(slug)
      filterMembers(slug)
      pushFilterUrl(slug)

      if (window.innerWidth < 600) closeDropdown()
    })
  })

  // ── Load more button ──────────────────────────────────────────────────────
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', loadMore)
  }

  // ── Back / forward navigateur ─────────────────────────────────────────────
  window.addEventListener('popstate', (e) => {
    const slug = e.state?.teamFilter || 'all'
    const btn  = teamFilter.querySelector(`[js-team-filter-btn][data-filter="${slug}"]`)
    if (!btn) return
    if (labelEl) labelEl.textContent = btn.dataset.label || btn.textContent.trim()
    if (titleEl) titleEl.textContent = btn.dataset.title || btn.textContent.trim()
    setActiveBtn(slug)
    filterMembers(slug)
  })

  // ── Init ──────────────────────────────────────────────────────────────────
  if (labelEl) labelEl.dataset.default = labelEl.textContent

  const urlFilter = window._teamFilter || null

  if (urlFilter) {
    // Pré-filtrage depuis l'URL — sans animation
    const preBtn = teamFilter.querySelector(`[js-team-filter-btn][data-filter="${urlFilter}"]`)
    if (preBtn) {
      const slug  = preBtn.dataset.filter
      const label = preBtn.dataset.label || preBtn.textContent.trim()
      const title = preBtn.dataset.title || preBtn.textContent.trim()

      if (labelEl) labelEl.textContent = label
      if (titleEl) titleEl.textContent = title

      setActiveBtn(slug)
      currentFilter = slug
      currentPage   = 1

      const filtered = getFilteredItems(slug)
      memberItems.forEach(item => {
        const idx = filtered.indexOf(item)
        item.style.display = (idx !== -1 && idx < PER_PAGE) ? '' : 'none'
      })

      updateLoadMore(filtered, 1)
      history.replaceState({ teamFilter: slug }, '', window.location.href)
    }
  } else {
    // "All" par défaut — affiche les 12 premiers
    const allBtn = teamFilter.querySelector('[js-team-filter-btn][data-filter="all"]')
    if (allBtn) allBtn.classList.add('is-active')

    const filtered = getFilteredItems('all')
    memberItems.forEach((item, i) => {
      item.style.display = i < PER_PAGE ? '' : 'none'
    })

    updateLoadMore(filtered, 1)
    history.replaceState({ teamFilter: 'all' }, '', window.location.href)
  }
}
