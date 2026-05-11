// ── Load More générique ───────────────────────────────────────────────────────
// Sur chaque [js-loadmore-section] :
//   data-action      → action AJAX WordPress
//   data-*           → autres params POST (author-id, section, item-type…)
// À l'intérieur :
//   [js-loadmore-grid] → conteneur où injecter les items
//   [js-loadmore-btn]  → bouton déclencheur

export function initLoadMore(root = document) {
  root.querySelectorAll('[js-loadmore-section]:not([js-lm-ready])').forEach(section => {
    section.setAttribute('js-lm-ready', '')

    const btn  = section.querySelector('[js-loadmore-btn]')
    const grid = section.querySelector('[js-loadmore-grid]')
    if (!btn || !grid) return

    let page = 1

    btn.addEventListener('click', async () => {
      page++
      btn.disabled = true

      // FormData → multipart, PHP popule $_POST normalement
      const fd = new FormData()
      fd.append('action', section.dataset.action)
      fd.append('nonce',  ajax.nonce)
      fd.append('page',   page)

      // Transfère tous les data-* (sauf action) comme params POST
      for (const [key, val] of Object.entries(section.dataset)) {
        if (key !== 'action') fd.append(key, val)
      }

      try {
        const res  = await fetch(ajax.url, { method: 'POST', body: fd })
        const data = await res.json()

        grid.insertAdjacentHTML('beforeend', data.html)

        // AOS sur les nouveaux éléments
        grid.querySelectorAll('.aos:not(.animated)').forEach(el => el.classList.add('animated'))

        // Hover animations sur les nouveaux items (is-entering / is-leaving)
        if (typeof window.initPublicationAnimation === 'function') window.initPublicationAnimation(grid)
        if (typeof window.initProjectsAnimation    === 'function') window.initProjectsAnimation(grid)

        if (!data.hasMore) {
          btn.style.display = 'none'
        } else {
          btn.disabled = false
        }
      } catch (e) {
        console.error('[LoadMore] error:', e)
        btn.disabled = false
      }
    })
  })
}

// Init au chargement du DOM (safe avec type="module")
document.addEventListener('DOMContentLoaded', () => initLoadMore())

// Exposé pour ré-init après injection AJAX (filtre projets, etc.)
window.initLoadMore = initLoadMore
