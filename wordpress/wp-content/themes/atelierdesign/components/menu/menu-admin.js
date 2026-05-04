/**
 * menu-admin.js
 *
 * Badge dynamique dans le handle de chaque nav_item :
 *   Link    → [Link]    — <texte>   <description?>
 *   Submenu → [Submenu] — <label>   <description?>
 *
 * Contexte : le thème utilise acf-lazy-layouts.js qui, au collapse,
 * DÉTACHE complètement .acf-fields du DOM (les inputs disparaissent).
 * Au expand, il réattache le .acf-fields original.
 *
 * Solution :
 *   – Mettre en CACHE les valeurs dans des data-attributes sur .layout
 *     (les data-attributes survivent au detach/reattach des champs)
 *   – Lire le cache quand les inputs sont absents (état stripped)
 *   – Hooker acf.addAction('show', ctx='collapse') pour réinjecter
 *     APRÈS que lazy-layouts ait réattaché les champs (priority 20)
 *   – Hooker acf.addAction('hide', ctx='collapse') pour mettre à jour
 *     le cache AVANT que lazy-layouts ne stripe (priority 5)
 */
(function ($) {

  if (typeof acf === 'undefined') return;

  var MENU_PREFIX = 'field-menu';
  var WATCHED     = ['label', 'description', 'link-description'];

  // ── Utilitaires ─────────────────────────────────────────────────────────────

  function esc(str) {
    return String(str)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function isMenuLayout(layout) {
    return !!layout.closest('[data-key^="' + MENU_PREFIX + '"]');
  }

  /**
   * Retourne [data-name=X] appartenant DIRECTEMENT à ce layout
   * (pas aux sous-layouts). Retourne null si les champs sont stripped.
   */
  function getOwnField(layout, name) {
    var all = layout.querySelectorAll('[data-name="' + name + '"]');
    for (var i = 0; i < all.length; i++) {
      if (all[i].closest('.layout') === layout) return all[i];
    }
    return null;
  }

  // ── Cache (data-attributes sur .layout) ─────────────────────────────────────
  // Les data-attributes survivent au detach/reattach de lazy-layouts.

  function cacheValues(layout) {
    var typeField = getOwnField(layout, 'type');
    var typeInput = typeField ? typeField.querySelector('input[type="radio"]:checked') : null;
    if (typeInput) layout.dataset.menuType = typeInput.value;

    if ((layout.dataset.menuType || 'link') === 'link') {
      var lf   = getOwnField(layout, 'link');
      var ltEl = lf ? lf.querySelector('.link-title') : null;
      if (ltEl) layout.dataset.menuLinkText = ltEl.textContent.trim();

      var ldf  = getOwnField(layout, 'link-description');
      var ldIn = ldf ? ldf.querySelector('input') : null;
      if (ldIn) layout.dataset.menuLinkDesc = ldIn.value.trim();

    } else {
      var labf  = getOwnField(layout, 'label');
      var labIn = labf ? labf.querySelector('input') : null;
      if (labIn) layout.dataset.menuLabel = labIn.value.trim();

      var df   = getOwnField(layout, 'description');
      var dIn  = df ? df.querySelector('input') : null;
      if (dIn) layout.dataset.menuDesc = dIn.value.trim();
    }
  }

  // ── Construction du badge ────────────────────────────────────────────────────
  // Lit les inputs si disponibles (layout expanded), sinon lit le cache.

  function buildHTML(layout) {
    // Type : input d'abord, cache ensuite
    var typeField = getOwnField(layout, 'type');
    var typeInput = typeField ? typeField.querySelector('input[type="radio"]:checked') : null;
    var type      = typeInput ? typeInput.value : (layout.dataset.menuType || 'link');

    // Mettre à jour le cache du type si on l'a lu en direct
    if (typeInput) layout.dataset.menuType = type;

    if (type === 'link') {
      var lf      = getOwnField(layout, 'link');
      var ltEl    = lf ? lf.querySelector('.link-title') : null;
      var lText   = ltEl !== null ? ltEl.textContent.trim() : (layout.dataset.menuLinkText || '');
      if (ltEl)   layout.dataset.menuLinkText = lText;

      var ldf     = getOwnField(layout, 'link-description');
      var ldIn    = ldf ? ldf.querySelector('input') : null;
      var desc    = ldIn !== null ? ldIn.value.trim() : (layout.dataset.menuLinkDesc || '');
      if (ldIn)   layout.dataset.menuLinkDesc = desc;

      var h = '<span class="acf-field-ad-menu">Link</span>';
      if (lText) h += ' \u2014 ' + esc(lText);
      if (desc)  h += ' <small class="acf-field-ad-desc">' + esc(desc) + '</small>';
      return h;
    }

    var labf    = getOwnField(layout, 'label');
    var labIn   = labf ? labf.querySelector('input') : null;
    var label   = labIn !== null ? labIn.value.trim() : (layout.dataset.menuLabel || '');
    if (labIn)  layout.dataset.menuLabel = label;

    var df      = getOwnField(layout, 'description');
    var dIn     = df ? df.querySelector('input') : null;
    var desc2   = dIn !== null ? dIn.value.trim() : (layout.dataset.menuDesc || '');
    if (dIn)    layout.dataset.menuDesc = desc2;

    var h2 = '<span class="acf-field-ad-menu">Submenu</span>';
    if (label) h2 += ' \u2014 ' + esc(label);
    if (desc2) h2 += ' <small class="acf-field-ad-desc">' + esc(desc2) + '</small>';
    return h2;
  }

  // ── Injection ────────────────────────────────────────────────────────────────

  function injectBadge(layout) {
    if (!layout || layout.classList.contains('acf-clone')) return;
    if (!isMenuLayout(layout)) return;

    var handle = layout.querySelector('.acf-fc-layout-handle');
    if (!handle) return;

    var info = handle.querySelector('.acf-field-ad-info');
    if (!info) {
      info = document.createElement('span');
      info.className = 'acf-field-ad-info';
      handle.appendChild(info);
    }
    info.innerHTML = buildHTML(layout);
  }

  function initLayout(layout) {
    if (!layout || layout.classList.contains('acf-clone')) return;
    if (!isMenuLayout(layout)) return;
    injectBadge(layout);
    setupLinkObs(layout);
  }

  // ── Observer ciblé sur .link-title ───────────────────────────────────────────

  function setupLinkObs(layout) {
    var lf = getOwnField(layout, 'link');
    if (!lf) return;
    var ltEl = lf.querySelector('.link-title');
    if (!ltEl || ltEl.dataset.menuLinkObs) return;
    ltEl.dataset.menuLinkObs = '1';

    new MutationObserver(function () {
      var l = ltEl.closest('.layout');
      if (l && !l.classList.contains('acf-clone')) injectBadge(l);
    }).observe(ltEl, { childList: true, characterData: true, subtree: true });
  }

  // ── Auto-ajout d'un sous-item quand on bascule vers « submenu » ─────────────
  //
  // Quand l'éditeur passe type → "submenu" et que le flexible_content "items"
  // est vide (0 sous-items), on clique automatiquement le bouton d'ajout pour
  // créer un premier sous-item. Ça évite l'erreur « 0 / min:1 » sans forcer
  // une valeur min dans le champ PHP (ce qui déclencherait la même validation
  // sur les items cachés de type "link").

  function autoAddSubItemIfEmpty(layout) {
    var itemsField = getOwnField(layout, 'items');
    if (!itemsField) return;

    var existing = itemsField.querySelectorAll(
      '.acf-flexible-content .values .layout:not(.acf-clone)'
    );
    if (existing.length > 0) return;

    var addBtn = itemsField.querySelector(
      '.acf-flexible-content > .acf-actions > .button, ' +
      '.acf-flexible-content .acf-fc-add-layout, '       +
      '.acf-flexible-content > .acf-actions a.button'
    );
    if (!addBtn) return;

    setTimeout(function () { addBtn.click(); }, 80);
  }

  // ── Debounce par layout ───────────────────────────────────────────────────────
  // Les 3 méthodes d'écoute (native change, jQuery change, jQuery click) peuvent
  // toutes se déclencher sur le même événement. On déduplique via WeakMap pour
  // n'exécuter qu'une seule fois par cycle JS.

  var _typeChangePending = typeof WeakMap !== 'undefined' ? new WeakMap() : null;

  function handleTypeChange(layout) {
    if (!layout || layout.classList.contains('acf-clone')) return;
    if (!isMenuLayout(layout)) return;

    // Déduplique : si déjà planifié pour ce layout, on ignore
    if (_typeChangePending && _typeChangePending.get(layout)) return;
    if (_typeChangePending) _typeChangePending.set(layout, true);

    setTimeout(function () {
      if (_typeChangePending) _typeChangePending.delete(layout);

      injectBadge(layout);

      var typeField = getOwnField(layout, 'type');
      var typeInput = typeField ? typeField.querySelector('input[type="radio"]:checked') : null;
      if (typeInput && typeInput.value === 'submenu') {
        autoAddSubItemIfEmpty(layout);
      }
    }, 0);
  }

  // ── Hooks ACF ────────────────────────────────────────────────────────────────

  // Priority 5 : AVANT que lazy-layouts (p.10) ne stripe les champs
  // → on met à jour le cache avec les vraies valeurs des inputs
  acf.addAction('hide', function ($el, context) {
    if (context !== 'collapse') return;
    var layout = ($el && typeof $el.get === 'function') ? $el.get(0) : $el;
    if (!layout || layout.classList.contains('acf-clone') || !isMenuLayout(layout)) return;
    cacheValues(layout);
    injectBadge(layout); // badge visible même en collapsed (utilise le cache)
  }, 5);

  // Priority 20 : APRÈS que lazy-layouts (p.10) ait restauré les champs
  // → on réinjecte avec les vraies valeurs des inputs (de nouveau disponibles)
  acf.addAction('show', function ($el, context) {
    if (context !== 'collapse') return;
    var layout = ($el && typeof $el.get === 'function') ? $el.get(0) : $el;
    if (!layout || layout.classList.contains('acf-clone') || !isMenuLayout(layout)) return;
    // setTimeout 0 : s'assure qu'ACF a fini son propre traitement post-show
    setTimeout(function () {
      injectBadge(layout);
      setupLinkObs(layout); // les link-title sont de retour dans le DOM
    }, 0);
  }, 20);

  // Ready : init tous les layouts présents
  acf.addAction('ready', function () {
    document.querySelectorAll('.layout:not(.acf-clone)').forEach(function (layout) {
      initLayout(layout);
    });
  });

  // Append : nouveaux layouts ajoutés dynamiquement
  acf.addAction('append', function ($el) {
    var el = ($el && typeof $el.get === 'function') ? $el.get(0) : $el;
    if (!el) return;
    var targets = el.classList && el.classList.contains('layout')
      ? [el]
      : Array.from(el.querySelectorAll ? el.querySelectorAll('.layout:not(.acf-clone)') : []);
    targets.forEach(function (layout) {
      if (!layout.classList.contains('acf-clone')) initLayout(layout);
    });
  });

  // ── Event delegation ─────────────────────────────────────────────────────────

  // Champs texte : label, description, link-description
  document.addEventListener('input', function (e) {
    var el = e.target;
    if (el.tagName !== 'INPUT' && el.tagName !== 'TEXTAREA') return;
    var f = el.closest('[data-name]');
    if (!f || WATCHED.indexOf(f.getAttribute('data-name')) === -1) return;
    var layout = el.closest('.layout');
    if (layout && !layout.classList.contains('acf-clone') && isMenuLayout(layout)) injectBadge(layout);
  });

  // Champ type (button_group) — 3 stratégies pour couvrir toutes les versions d'ACF :
  //
  // ACF déclenche change() via jQuery sur :
  //   1. l'input radio lui-même  → capturé par addEventListener natif
  //   2. le wrapper .acf-field   → NOT capturé par addEventListener (el.type !== 'radio')
  //      → capturé par délégation jQuery sur [data-name="type"]
  //   3. fallback click sur les boutons (au cas où)

  // Méthode 1 : native change sur l'input radio
  document.addEventListener('change', function (e) {
    var el = e.target;
    if (!el || el.tagName !== 'INPUT' || el.type !== 'radio') return;
    if (!el.closest('[data-name="type"]')) return;
    var layout = el.closest('.layout');
    if (layout && !layout.classList.contains('acf-clone') && isMenuLayout(layout)) {
      handleTypeChange(layout);
    }
  });

  // Méthode 2 : jQuery delegation sur le wrapper field (capte this.$el.trigger('change') d'ACF)
  $(document).on('change', '[data-name="type"]', function () {
    var layout = $(this).closest('.layout')[0];
    if (layout && !layout.classList.contains('acf-clone') && isMenuLayout(layout)) {
      handleTypeChange(layout);
    }
  });

  // Méthode 3 : click direct sur les boutons du button_group
  $(document).on('click', '[data-name="type"] .acf-button-group li', function () {
    var layout = $(this).closest('.layout')[0];
    if (!layout || layout.classList.contains('acf-clone') || !isMenuLayout(layout)) return;
    // setTimeout 0 : laisse ACF cocher le radio avant qu'on le lise
    setTimeout(function () { handleTypeChange(layout); }, 0);
  });

  // ── Validation — ouvre les accordéons en erreur + marque les layouts ────────
  //
  // Après un échec de validation ACF, certains champs en erreur (.acf-error)
  // peuvent être dans des layouts repliés (invisible). On :
  //   1. Rouvre automatiquement les layouts repliés qui contiennent une erreur
  //   2. Ajoute .has-acf-error sur tous les layouts parents (même ouverts)
  //      pour afficher le liseré rouge dans le handle (voir menu-admin.css)

  function markAndExpandErrorLayouts() {
    // Enlève les marqueurs de la passe précédente
    document.querySelectorAll('[data-key^="field-menu"] .layout.has-acf-error')
      .forEach(function (l) { l.classList.remove('has-acf-error'); });

    // Trouve tous les champs en erreur dans nos flexibles
    document.querySelectorAll('[data-key^="field-menu"] .acf-field.acf-error')
      .forEach(function (field) {
        // Remonte tous les layouts ancêtres
        var el = field;
        while (el) {
          el = el.parentElement ? el.parentElement.closest('.layout') : null;
          if (!el) break;

          el.classList.add('has-acf-error');

          // Si le layout est replié (collapsed), on l'ouvre via ACF
          if (el.classList.contains('-collapsed')) {
            var $el = acf.$(el);
            var field_obj = acf.getField($el.find('.acf-field').first().data('key'));
            if (field_obj) {
              // Cherche le flexible_content parent et expand ce layout
              var $handle = $el.find('> .acf-fc-layout-handle');
              if ($handle.length) $handle.trigger('click');
            } else {
              // Fallback : retire la classe collapsed directement
              el.classList.remove('-collapsed');
              var fields = el.querySelector('.acf-fields');
              if (fields) fields.style.display = '';
            }
          }
        }
      });
  }

  // Se déclenche après la réponse de validation ACF (succès ou échec)
  acf.addAction('validation_failure', function () {
    // Petit délai : laisse ACF finir d'appliquer ses classes .acf-error
    setTimeout(markAndExpandErrorLayouts, 100);
  });

  // ── Safety net ───────────────────────────────────────────────────────────────
  // Au cas où un badge aurait disparu pour une raison inattendue.

  setInterval(function () {
    document.querySelectorAll('.layout:not(.acf-clone)').forEach(function (layout) {
      if (!isMenuLayout(layout)) return;
      var handle = layout.querySelector('.acf-fc-layout-handle');
      if (handle && !handle.querySelector('.acf-field-ad-info')) {
        injectBadge(layout);
      }
    });
  }, 300);

}(jQuery));
