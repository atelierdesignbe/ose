<?php

/**
 * ── Polylang helpers ──────────────────────────────────────────────────────────
 *
 * 1. Auto-création des traductions de termes pour toutes les langues actives.
 * 2. Masquer l'onglet "Most Used" dans les metaboxes de taxonomies.
 * 3. Masquer les liens "+ Add New ..." dans les metaboxes de taxonomies.
 */


/**
 * 1. Auto-création des traductions de termes.
 *
 * Quand un nouveau terme est créé sans traductions existantes,
 * on crée automatiquement une copie pour chaque autre langue active
 * et on lie toutes les copies via Polylang.
 *
 * Priorité 50 → s'exécute après Polylang (priorité ~10-20) qui aura
 * déjà assigné la langue au terme source.
 */
add_action('created_term', function (int $term_id, int $tt_id, string $taxonomy): void {

  // Polylang requis
  if (
    !function_exists('pll_languages_list') ||
    !function_exists('pll_set_term_language') ||
    !function_exists('pll_get_term_language') ||
    !function_exists('pll_save_term_translations')
  ) return;

  // Ignorer les taxonomies internes de WordPress et Polylang
  $skip = [
    'category', 'post_tag', 'nav_menu', 'link_category', 'post_format',
    'language', 'term_language', 'post_translations', 'term_translations',
  ];
  if (in_array($taxonomy, $skip, true)) return;

  // Récupère la langue assignée par Polylang (il l'assigne en priorité ~10)
  $source_lang = pll_get_term_language($term_id, 'slug');

  // Fallback : utiliser la langue par défaut si Polylang n'a rien assigné
  if (!$source_lang) {
    $source_lang = pll_default_language();
    pll_set_term_language($term_id, $source_lang);
  }

  // Si des traductions existent déjà → ne rien faire
  $existing = function_exists('pll_get_term_translations')
    ? pll_get_term_translations($term_id)
    : [];
  if (count($existing) > 1) return;

  $term = get_term($term_id, $taxonomy);
  if (!$term || is_wp_error($term)) return;

  $languages    = pll_languages_list(['fields' => 'slug']);
  $translations = [$source_lang => $term_id];

  foreach ($languages as $lang) {
    if ($lang === $source_lang) continue;

    // Slug suffixé pour éviter les conflits (ex: "poverty-fr")
    $slug   = sanitize_title($term->name) . '-' . $lang;
    $result = wp_insert_term($term->name, $taxonomy, ['slug' => $slug]);

    if (!is_wp_error($result)) {
      $new_id = (int) $result['term_id'];
      pll_set_term_language($new_id, $lang);
      $translations[$lang] = $new_id;
    }
  }

  // Lier toutes les traductions dans un groupe Polylang
  pll_save_term_translations($translations);

}, 50, 3);


/**
 * 2 & 3. Admin UI : masquer "Most Used" et "+ Add New ..."
 *         dans toutes les metaboxes de taxonomies.
 */
add_action('admin_head', function (): void {
  echo '<style>
    /* Onglet "Most Used" dans les metaboxes de taxonomies */
    .taxonomy-tabs li.hide-if-no-js { display: none !important; }

    /* Liens "+ Add New [Taxonomy]" (taxonomies hiérarchiques) */
    .taxonomy-add-new { display: none !important; }

    /* Formulaire d\'ajout rapide de tag (taxonomies non-hiérarchiques) */
    .tagadd-wrap { display: none !important; }
  </style>';
});
