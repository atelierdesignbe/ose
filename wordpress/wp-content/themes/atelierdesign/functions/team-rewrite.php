<?php

/**
 * URLs pré-filtrées de la page Team
 *
 * Permet des URLs du type /team/administrative-team/ qui ouvrent
 * la page team pré-filtrée sur le membre type correspondant.
 *
 * Fonctionnement :
 *  1. WordPress parse /team/{slug}/ → pense que c'est un CPT author
 *  2. Le filtre 'request' intercepte et vérifie si {slug} est un terme member_type
 *  3. Si oui → on remplace les query vars pour servir la page team avec le filtre actif
 *  4. Si non → on laisse passer (= page author normale)
 */
add_filter('request', function (array $query_vars): array {

    // On n'intercepte que si WordPress a parsé ça comme un CPT author
    $slug = $query_vars['ose_author']
         ?? ($query_vars['name'] ?? null);

    if (! $slug) return $query_vars;

    // On vérifie que c'est bien dans le contexte du post_type author
    $pt = $query_vars['post_type'] ?? '';
    if ($pt && $pt !== 'author') return $query_vars;

    // Est-ce que ce slug correspond à un terme member_type ?
    $term = get_term_by('slug', $slug, 'member_type');
    if (! $term) return $query_vars; // Non → page auteur normale, on ne touche rien

    // Oui → on cherche la page Team (template)
    $team_pages = get_posts([
        'post_type'      => 'page',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'templates/team.php',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'post_status'    => 'publish',
    ]);

    if (empty($team_pages)) return $query_vars;

    // On remplace les query vars pour servir la page team + passer le filtre
    return [
        'page_id'            => (int) $team_pages[0],
        'member_type_filter' => $slug,
    ];
});


/**
 * Enregistre member_type_filter comme query var publique
 * (nécessaire pour que get_query_var() le retourne)
 */
add_filter('query_vars', function (array $vars): array {
    if (! in_array('member_type_filter', $vars, true)) {
        $vars[] = 'member_type_filter';
    }
    return $vars;
});


/**
 * Génère les liens canoniques vers les URLs pré-filtrées
 * Helper utilisable dans les templates : team_filter_url('administrative-team')
 */
function team_filter_url(string $term_slug): string
{
    $team_pages = get_posts([
        'post_type'      => 'page',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'templates/team.php',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'post_status'    => 'publish',
    ]);

    if (empty($team_pages)) return '';

    $base = trailingslashit(get_permalink($team_pages[0]));
    return $base . trailingslashit($term_slug);
}
