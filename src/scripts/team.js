// Team page — filtrage JS par taxonomie member_type
const teamFilter = document.querySelector('[js-team-filter]')

if (teamFilter) {
  const filterBtns  = teamFilter.querySelectorAll('[js-team-filter-btn]')
  const memberItems = [...teamFilter.querySelectorAll('[js-team-member]')]
  const labelEl     = teamFilter.querySelector('[js-team-filter-label]')
  const titleEl     = teamFilter.querySelector('[js-team-title]')
  const expandBtn   = teamFilter.querySelector('[js-expand-button]')
  const expandEl    = teamFilter.querySelector('[js-expand]')

  // Parse le data-types JSON sur chaque item (tableau de slugs)
  function getItemTypes(item) {
    try { return JSON.parse(item.dataset.types || '[]') } catch { return [] }
  }

  // ── Filtrage avec animation ───────────────────────────────────────────────
  function filterMembers(slug) {
    // 1. Fade-out de tous les items actuellement visibles
    memberItems.forEach(item => {
      item.style.opacity = '0'
      item.style.transform = 'translateY(10px)'
    })

    setTimeout(() => {
      // 2. Cacher / montrer selon le filtre
      // Un auteur peut appartenir à plusieurs catégories → on vérifie le tableau
      const toShow = []
      memberItems.forEach(item => {
        const types = getItemTypes(item)
        const match = slug === 'all' || types.includes(slug)
        if (match) {
          item.style.display = ''
          toShow.push(item)
        } else {
          item.style.display = 'none'
        }
      })

      // 3. Fade-in des items visibles avec stagger
      toShow.forEach((item, i) => {
        setTimeout(() => {
          item.style.opacity = '1'
          item.style.transform = 'translateY(0)'
        }, i * 40)
      })
    }, 220) // légèrement moins que la durée de la transition CSS (250ms)
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

  // ── Click sur un filtre ───────────────────────────────────────────────────
  // ── Met à jour l'URL sans rechargement ───────────────────────────────────
  function pushFilterUrl(slug) {
    const base = window.location.pathname
      .replace(/\/$/, '')                        // retire le trailing slash
      .replace(/\/[a-z0-9-]+$/, '')              // retire un éventuel slug déjà présent
      .replace(/\/team\/[a-z0-9-]*$/, '/team')   // normalise la base /team

    const teamBase = base.endsWith('/team') ? base : base
    const url = slug === 'all'
      ? teamBase + '/'
      : teamBase + '/' + slug + '/'

    history.pushState({ teamFilter: slug }, '', url)
  }

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

  // Bouton back/forward du navigateur
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

  // Pre-filtre depuis l'URL (/team/administrative-team/) sans animation
  const urlFilter = window._teamFilter || null

  if (urlFilter) {
    const preBtn = teamFilter.querySelector(`[js-team-filter-btn][data-filter="${urlFilter}"]`)
    if (preBtn) {
      const slug  = preBtn.dataset.filter
      const label = preBtn.dataset.label || preBtn.textContent.trim()
      const title = preBtn.dataset.title || preBtn.textContent.trim()

      if (labelEl) labelEl.textContent = label
      if (titleEl) titleEl.textContent = title

      setActiveBtn(slug)

      // Application immédiate sans animation (page déjà chargée pour ce filtre)
      memberItems.forEach(item => {
        const types = getItemTypes(item)
        item.style.display = (slug === 'all' || types.includes(slug)) ? '' : 'none'
      })

      // Initialise l'état history pour que popstate fonctionne
      history.replaceState({ teamFilter: slug }, '', window.location.href)
    }
  } else {
    // "All" actif par défaut
    const allBtn = teamFilter.querySelector('[js-team-filter-btn][data-filter="all"]')
    if (allBtn) allBtn.classList.add('is-active')
    history.replaceState({ teamFilter: 'all' }, '', window.location.href)
  }
}
