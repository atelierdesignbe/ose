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
 * Exclure les publications externes des résultats de recherche
 * (elles redirigent vers un lien externe, pas de page interne).
 */
add_filter('posts_where', function (string $where, WP_Query $query): string {

    if (! $query->is_search() || ! $query->is_main_query() || is_admin()) {
        return $where;
    }

    global $wpdb;

    $where .= " AND NOT (
        {$wpdb->posts}.post_type = 'publication'
        AND EXISTS (
            SELECT 1 FROM {$wpdb->postmeta}
            WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
              AND {$wpdb->postmeta}.meta_key   = 'is-external'
              AND {$wpdb->postmeta}.meta_value = '1'
        )
    )";

    return $where;

}, 10, 2);
