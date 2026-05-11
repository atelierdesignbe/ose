<?php

/**
 * Inclure tous les CPTs dans la recherche WordPress
 *
 * Par défaut WP ne cherche que dans 'post' et 'page'.
 * Ce filtre étend la requête aux CPTs publiés et searchable du thème.
 */
add_action('pre_get_posts', function (WP_Query $query) {

    if (! $query->is_search() || ! $query->is_main_query() || is_admin()) {
        return;
    }

    $query->set('post_type', [
        'post',
        'page',
        'publication',
        'project',
        'event',
        'author',
    ]);

    $query->set('posts_per_page', 24);

}, 1);


/**
 * Supprimer le filtre de langue Polylang de la recherche
 *
 * Polylang ajoute dans le SQL final :
 *   LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)
 *   AND ( wp_term_relationships.term_taxonomy_id IN (N) )
 *
 * Cette condition exclut les CPTs sans langue assignée (le LEFT JOIN retourne NULL
 * → la condition est fausse → le post est invisible).
 *
 * On utilise posts_request (dernier filtre avant exécution) pour :
 *   1. Supprimer la condition term_taxonomy_id de Polylang
 *   2. Ajouter DISTINCT pour éviter les doublons dus au LEFT JOIN restant
 */
add_filter('posts_request', function (string $sql, WP_Query $query): string {

    if (! $query->is_search() || ! $query->is_main_query() || is_admin()) {
        return $sql;
    }

    // Supprimer la condition de langue Polylang : AND ( table.term_taxonomy_id IN (N,...) )
    $cleaned = preg_replace(
        '/\s*AND\s*\(\s*[\w_]+\.term_taxonomy_id\s+IN\s*\([0-9,\s]+\)\s*\)/i',
        '',
        $sql
    );

    // Si on a bien supprimé quelque chose, ajouter DISTINCT pour éviter
    // les doublons potentiels causés par le LEFT JOIN restant
    if ($cleaned !== $sql) {
        $cleaned = preg_replace(
            '/SELECT\s+SQL_CALC_FOUND_ROWS\s+(?!DISTINCT)/i',
            'SELECT SQL_CALC_FOUND_ROWS DISTINCT ',
            $cleaned
        );
    }

    return $cleaned;

}, 9999, 2);


/**
 * ── Fuzzy search (tolérance aux fautes de frappe) ────────────────────────────
 *
 * Algorithme Levenshtein appliqué aux titres de tous les posts publiés.
 * Distance max autorisée : ~30 % de la longueur du mot recherché.
 *   → "projet"     (6)  → max 2  → matche "project"   (distance 2) ✓
 *   → "publcation" (10) → max 3  → matche "publication"(distance 1) ✓
 *   → "auteur"     (6)  → max 2  → matche "author"     (distance 2) ✓
 *
 * Les IDs trouvés sont injectés dans le WHERE via OR ID IN (...),
 * sans toucher à la recherche LIKE existante.
 */

/**
 * Normalise une chaîne : minuscules + suppression des accents.
 * "Événement" → "evenement", "auteur" → "auteur".
 */
function ose_normalize( string $str ): string {
  $str = mb_strtolower( $str );
  $str = iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $str );
  return preg_replace( '/[^a-z0-9\s]/', '', $str );
}

/**
 * Retourne les IDs des posts dont le titre contient un mot
 * à distance Levenshtein ≤ seuil du terme recherché.
 *
 * @param string $raw_term  Terme brut saisi par l'utilisateur.
 * @param array  $post_types CPTs à inclure dans la recherche.
 * @return int[]
 */
function ose_fuzzy_post_ids( string $raw_term, array $post_types ): array {
  $raw_term = trim( $raw_term );
  if ( mb_strlen( $raw_term ) < 3 ) return [];

  global $wpdb;

  $types_in = implode( "','", array_map( 'esc_sql', $post_types ) );
  $rows     = $wpdb->get_results(
    "SELECT ID, post_title
     FROM {$wpdb->posts}
     WHERE post_status = 'publish'
     AND post_type IN ('{$types_in}')"
  );

  // Découpe la requête en mots (≥ 3 caractères)
  $words = array_filter(
    preg_split( '/\s+/', ose_normalize( $raw_term ) ),
    fn( $w ) => mb_strlen( $w ) >= 3
  );
  if ( empty( $words ) ) return [];

  $matching = [];

  foreach ( $rows as $row ) {
    $title_norm  = ose_normalize( $row->post_title );
    $title_words = preg_split( '/[\s\-_\/,;:.!?]+/', $title_norm );

    foreach ( $words as $word ) {
      $len      = mb_strlen( $word );
      // Seuil Algolia : 0 faute (≤3 chars), 1 faute (4-7 chars), 2 fautes (8+ chars)
      $max_dist = match(true) {
        $len <= 3 => 0,
        $len <= 7 => 1,
        default   => 2,
      };

      foreach ( $title_words as $tw ) {
        if ( mb_strlen( $tw ) < 2 ) continue;

        // Correspondance exacte rapide (évite levenshtein inutile)
        if ( $word === $tw ) {
          continue 3; // déjà dans résultats LIKE, inutile de l'ajouter
        }

        if ( $max_dist > 0 && levenshtein( $word, $tw ) <= $max_dist ) {
          $matching[] = (int) $row->ID;
          continue 3; // passe au $row suivant
        }
      }

      // Test de sous-chaîne : "publi" retrouve "publication"
      if ( $len >= 4 && strpos( $title_norm, $word ) !== false ) {
        continue 2; // déjà couvert par LIKE, pas besoin d'ajouter
      }
    }
  }

  return array_unique( $matching );
}

add_filter( 'posts_search', function ( string $search, WP_Query $query ): string {
  if ( ! $query->is_search() || ! $query->is_main_query() || is_admin() ) return $search;

  $term = trim( get_query_var( 's' ) );
  if ( mb_strlen( $term ) < 3 ) return $search;

  $post_types = [ 'post', 'page', 'publication', 'project', 'event', 'author' ];
  $fuzzy_ids  = ose_fuzzy_post_ids( $term, $post_types );

  if ( empty( $fuzzy_ids ) ) return $search;

  global $wpdb;
  $ids_sql = implode( ',', $fuzzy_ids );

  // Retire le "AND" initial du $search WP, puis englobe les deux
  // conditions (exact LIKE + fuzzy IDs) dans un seul AND (... OR ...).
  $inner  = trim( preg_replace( '/^\s*AND\s*/i', '', trim( $search ) ) );
  $search = " AND ( {$inner} OR {$wpdb->posts}.ID IN ({$ids_sql}) )";

  return $search;
}, 10, 2 );


