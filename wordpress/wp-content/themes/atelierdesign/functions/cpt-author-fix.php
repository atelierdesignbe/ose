<?php

/**
 * Fix pour le CPT 'author' — terme réservé par WordPress.
 *
 * WordPress utilise 'author' en interne pour ses query vars et templates.
 * Ces filtres forcent le bon comportement pour le CPT author.
 */

/**
 * Force le template single-author.php quand on visualise un CPT author.
 * Sans ce filtre, WordPress peut servir is_author() (archive d'utilisateur)
 * au lieu de is_singular('author').
 */
add_filter( 'template_include', function ( $template ) {
    if ( is_singular( 'author' ) ) {
        $custom = locate_template( 'single-author.php' );
        if ( $custom ) {
            return $custom;
        }
    }
    return $template;
}, 99 );


/**
 * Empêche WordPress de confondre l'archive d'auteur WP (/author/slug/)
 * avec les posts du CPT author quand on est sur une page is_author().
 *
 * Si on est sur /author/{slug}/ et que ce slug correspond à un post
 * du CPT author, on redirige vers le vrai permalink du CPT.
 */
add_action( 'template_redirect', function () {
    if ( ! is_author() ) return;

    $author_name = get_query_var( 'author_name' );
    if ( ! $author_name ) return;

    // Cherche un post CPT 'author' avec ce slug
    $post = get_page_by_path( $author_name, OBJECT, 'author' );
    if ( $post ) {
        wp_redirect( get_permalink( $post->ID ), 301 );
        exit;
    }
} );

/**
 * Les membres archivés n'ont pas d'URL front-end : redirection 301 vers la home.
 * Priorité 5 = avant la redirection /author/{slug}/ ci-dessous.
 */
add_action( 'template_redirect', function () {
    if ( ! is_singular( 'author' ) ) return;

    $queried = get_queried_object();
    if ( ! $queried || ! has_term( 'archived', 'member_status', $queried->ID ) ) return;

    wp_redirect( home_url(), 301 );
    exit;
}, 5 );


/**
 * Ajoute 'ose_author' comme query var publique pour que WordPress
 * puisse router /team/{slug}/ correctement.
 */
add_filter( 'query_vars', function ( $vars ) {
    if ( ! in_array( 'ose_author', $vars ) ) {
        $vars[] = 'ose_author';
    }
    return $vars;
} );


/**
 * Flush rewrite rules si la version a changé (à incrémenter après
 * tout changement de structure de permaliens du CPT).
 */
add_action( 'init', function () {
    $version = '2.0';
    if ( get_option( 'ose_author_rewrite_version' ) !== $version ) {
        flush_rewrite_rules();
        update_option( 'ose_author_rewrite_version', $version );
    }
}, 100 );


/**
 * Exclut les membres archivés des résultats de recherche front-end.
 * On récupère leurs IDs et on les ajoute à post__not_in sur la requête principale.
 */
add_action( 'pre_get_posts', function ( WP_Query $query ) {
    if ( is_admin() || ! $query->is_search() ) return;
    // is_main_query() retiré : Polylang peut cloner la query, on filtre toutes les search queries front

    $archived_tax_ids = ose_member_status_term_ids( 'archived' );
    if ( empty( $archived_tax_ids ) ) return;

    $archived_post_ids = get_posts( [
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any',
        'lang'           => '',          // contourne Polylang pour récupérer tous les membres archivés
        'tax_query'      => [ [
            'taxonomy' => 'member_status',
            'field'    => 'term_id',
            'terms'    => $archived_tax_ids,
            'operator' => 'IN',
        ] ],
    ] );

    if ( empty( $archived_post_ids ) ) return;

    $existing = (array) $query->get( 'post__not_in' );
    $query->set( 'post__not_in', array_merge( $existing, array_map( 'intval', $archived_post_ids ) ) );
}, 999 ); // priorité 999 = après Polylang


/**
 * Exclut le CPT 'external_author' de la gestion Polylang.
 * Exclut aussi la taxonomie 'member_status' (partagée entre langues, pas traduite).
 */
add_filter( 'pll_get_post_types', function ( $post_types, $is_settings ) {
    unset( $post_types['external_author'] );
    return $post_types;
}, 10, 2 );

add_filter( 'pll_get_taxonomies', function ( $taxonomies, $is_settings ) {
    unset( $taxonomies['member_status'] );
    return $taxonomies;
}, 10, 2 );


/**
 * Retourne tous les term_id de member_status dont le nom contient $search.
 * Gère les doublons créés par Polylang (FR + EN → même nom, slugs différents).
 */
function ose_member_status_term_ids( $search ) {
    $all = get_terms( [ 'taxonomy' => 'member_status', 'hide_empty' => false ] );
    if ( is_wp_error( $all ) || empty( $all ) ) return [];
    $matching = array_filter( $all, function( $t ) use ( $search ) {
        return stripos( $t->name, $search ) !== false;
    } );
    return array_values( array_map( 'intval', wp_list_pluck( $matching, 'term_id' ) ) );
}


/**
 * Fix ACF relationship field "author" (project) : members par langue + external_author sans langue.
 *
 * Polylang applique son filtre langue globalement sur la WP_Query principale.
 * On contourne en pré-calculant les IDs via deux sous-requêtes indépendantes,
 * puis on injecte post__in + lang='' pour neutraliser Polylang sur la requête ACF.
 *
 * - members (CPT author)          → filtrés par la langue active
 * - external people (CPT external_author) → tous, sans filtre de langue
 */
add_filter( 'acf/fields/relationship/query/name=author', function ( $args ) {
    if ( ! function_exists( 'pll_current_language' ) ) return $args;

    $lang             = pll_current_language();
    $archived_ids_tax = ose_member_status_term_ids( 'archived' ); // tous les termes "archived" (FR + EN)

    $base_member_args = [
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any',
        'lang'           => $lang ?: '',
    ];

    // 1a. Actifs = ceux qui ne sont pas dans un terme "archived" + membres sans statut (legacy)
    $member_ids = get_posts( array_merge( $base_member_args, [
        'tax_query' => ! empty( $archived_ids_tax ) ? [ [
            'taxonomy'         => 'member_status',
            'field'            => 'term_id',
            'terms'            => $archived_ids_tax,
            'operator'         => 'NOT IN',
            'include_children' => false,
        ] ] : [],
    ] ) );

    // 1b. Archivés — tous les termes "archived" possibles
    $archived_member_ids = get_posts( array_merge( $base_member_args, [
        'tax_query' => ! empty( $archived_ids_tax ) ? [ [
            'taxonomy'         => 'member_status',
            'field'            => 'term_id',
            'terms'            => $archived_ids_tax,
            'operator'         => 'IN',
            'include_children' => false,
        ] ] : [],
    ] ) );

    // 2. External people : tous, on neutralise Polylang via __return_empty_array
    //    (pll_get_post_types vide = Polylang ne gère aucun type = pas de filtre langue)
    add_filter( 'pll_get_post_types', '__return_empty_array', 999 );
    $external_ids = get_posts( [
        'post_type'      => 'external_author',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any',
    ] );
    remove_filter( 'pll_get_post_types', '__return_empty_array', 999 );

    // Ordre : actifs → archivés → external people
    $all_ids = array_merge(
        $member_ids          ?: [],
        $archived_member_ids ?: [],
        $external_ids        ?: []
    );

    $args['post__in'] = ! empty( $all_ids ) ? $all_ids : [ 0 ];
    $args['orderby']  = 'post__in'; // respecte l'ordre défini ci-dessus
    $args['lang']     = ''; // neutralise Polylang sur la requête principale ACF

    return $args;
} );


/**
 * Neutralise le filtre langue Polylang pour toute WP_Query qui implique external_author,
 * notamment le pipeline de chargement/validation d'ACFE (format_value, validate_value…)
 * qui peut utiliser get_posts() — et donc passer par pre_get_posts — avec post_type mixte.
 *
 * Règle : on retire la clause langue UNIQUEMENT si :
 *   a) la requête cible exclusivement external_author (sous-requête interne), OU
 *   b) un post__in est déjà défini (chargement de valeurs sauvegardées : les IDs
 *      sont déjà les bons, pas besoin du filtre langue pour les restreindre).
 */
add_action( 'pre_get_posts', function ( WP_Query $query ) {
    $post_types = (array) $query->get( 'post_type' );
    if ( ! in_array( 'external_author', $post_types, true ) ) return;

    $post_in = $query->get( 'post__in' );

    if ( $post_types !== [ 'external_author' ] && empty( $post_in ) ) return;

    $tax_query = $query->get( 'tax_query' );
    if ( ! is_array( $tax_query ) ) return;

    $clean = array_values( array_filter( $tax_query, function ( $clause ) {
        return ! is_array( $clause )
            || ! isset( $clause['taxonomy'] )
            || $clause['taxonomy'] !== 'language';
    } ) );

    $query->set( 'tax_query', $clean );
    $query->set( 'lang', '' );
}, 999 );


// =============================================================================
// Membres archivés : pas d'URL front-end
// =============================================================================

/**
 * Retourne '#' comme permalink pour les membres archivés.
 * - Front : les templates qui appellent get_permalink() sans check archived reçoivent '#'
 * - Admin : le permalien affiché dans l'éditeur affiche '#' au lieu d'une URL réelle
 */
add_filter( 'post_type_link', function ( $url, $post ) {
    if ( $post->post_type !== 'author' ) return $url;
    if ( has_term( 'archived', 'member_status', $post->ID ) ) return '#';
    return $url;
}, 10, 2 );

/**
 * Supprime le bouton "View Post" dans la barre admin pour les membres archivés.
 */
add_filter( 'post_row_actions', function ( $actions, $post ) {
    if ( $post->post_type === 'author' && has_term( 'archived', 'member_status', $post->ID ) ) {
        unset( $actions['view'] );
    }
    return $actions;
}, 10, 2 );


// =============================================================================
// Migration one-shot : assigne le terme "active" à tous les membres sans statut
// =============================================================================

/**
 * Tourne une seule fois en admin (version à incrémenter si besoin de relancer).
 * Parcourt tous les CPT 'author' sans terme member_status et leur assigne "active".
 */
add_action( 'admin_init', function () {
    $version = 'v1';
    if ( get_option( 'ose_member_status_migration' ) === $version ) return;

    $active_term = get_term_by( 'slug', 'active', 'member_status' );
    if ( ! $active_term ) return; // termes pas encore créés

    $members = get_posts( [
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'post_status'    => 'any',
        'tax_query'      => [ [
            'taxonomy' => 'member_status',
            'operator' => 'NOT EXISTS',
        ] ],
    ] );

    foreach ( $members as $id ) {
        wp_set_object_terms( $id, [ $active_term->term_id ], 'member_status', false );
    }

    update_option( 'ose_member_status_migration', $version );
} );


// =============================================================================
// Sync champ ACF "is_active_member" (toggle) ↔ taxonomie "member_status"
// =============================================================================

/**
 * À la sauvegarde : on lit la valeur du toggle ACF et on met à jour la taxonomie.
 * Priorité 20 = après qu'ACF ait sauvegardé ses valeurs (priorité défaut = 10).
 */
add_action( 'acf/save_post', function ( $post_id ) {
    if ( get_post_type( $post_id ) !== 'author' ) return;

    // get_field() déclenche acf/load_value qui lit la taxonomie (pas encore à jour)
    // → boucle circulaire. On lit le meta brut directement après que ACF a sauvegardé.
    $raw       = get_post_meta( $post_id, 'is_active_member', true );
    $is_active = ( $raw == 1 ); // ACF stocke '1' ou '0' en base
    $slug      = $is_active ? 'active' : 'archived';
    $term      = get_term_by( 'slug', $slug, 'member_status' );

    if ( $term ) {
        wp_set_object_terms( $post_id, [ $term->term_id ], 'member_status', false );
    }
}, 20 );

/**
 * Au chargement : on lit la taxonomie et on retourne la valeur correspondante au toggle.
 * Si aucun terme → on considère Active par défaut.
 */
add_filter( 'acf/load_value/name=is_active_member', function ( $value, $post_id, $field ) {
    if ( get_post_type( $post_id ) !== 'author' ) return $value;

    $terms = wp_get_object_terms( $post_id, 'member_status', [ 'fields' => 'slugs' ] );

    if ( is_wp_error( $terms ) || empty( $terms ) ) return 1; // défaut : Active

    return in_array( 'archived', $terms, true ) ? 0 : 1;
}, 10, 3 );


// =============================================================================
// Séparation visuelle Actifs / Archivés dans le picker ACF relationship "author"
// =============================================================================

/**
 * Séparation visuelle Actifs / Archivés dans le picker ACF.
 *
 * acf/fields/relationship/result n'est pas déclenché par ACFE Pro.
 * On passe par JS : on injecte les IDs archivés en variable globale,
 * puis un MutationObserver + ajaxComplete appliquent le style dès que
 * les items sont rendus dans le DOM.
 */
add_action( 'admin_footer', function () {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->post_type, [ 'project', 'publication' ], true ) ) return;

    $archived_tax_ids = ose_member_status_term_ids( 'archived' );
    $archived_ids     = [];

    if ( ! empty( $archived_tax_ids ) ) {
        $archived_ids = get_posts( [
            'post_type'      => 'author',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'any',
            'tax_query'      => [ [
                'taxonomy' => 'member_status',
                'field'    => 'term_id',
                'terms'    => $archived_tax_ids,
                'operator' => 'IN',
            ] ],
        ] );
    }

    // Debug : tous les termes de member_status
    $all_terms    = get_terms( [ 'taxonomy' => 'member_status', 'hide_empty' => false ] );
    $debug_terms  = is_wp_error( $all_terms ) ? [] : array_map( function( $t ) {
        return [ 'id' => $t->term_id, 'name' => $t->name, 'slug' => $t->slug ];
    }, $all_terms );

    $ids_json        = wp_json_encode( array_map( 'intval', $archived_ids ) );
    $debug_terms_json = wp_json_encode( $debug_terms );
    $debug_tax_ids   = wp_json_encode( $archived_tax_ids );
    ?>
    <script>
    jQuery(function($) {
        var archivedIds  = <?= $ids_json ?>;
        var allTerms     = <?= $debug_terms_json ?>;
        var archivedTaxIds = <?= $debug_tax_ids ?>;
        console.log('[OSE] member_status terms en base:', allTerms);
        console.log('[OSE] term_ids "archived" trouvés:', archivedTaxIds);
        console.log('[OSE] archivedIds (member posts):', archivedIds);
        if (!archivedIds.length) {
            console.warn('[OSE] archivedIds vide — voir les logs ci-dessus pour diagnostiquer.');
            return;
        }

        function styleArchived() {
            // Sélecteurs larges : compatible ACF 6.x et ACFE 0.9.x
            $('[data-id]').each(function() {
                var $el = $(this);
                var id  = parseInt($el.attr('data-id'), 10);
                if (!id || archivedIds.indexOf(id) === -1) return;
                if ($el.hasClass('ose-archived')) return;

                $el.addClass('ose-archived').css({ opacity: '0.45', fontStyle: 'italic' });

                // Cherche le texte dans n'importe quel span/a enfant
                var $label = $el.find('span, a, div').filter(function() {
                    return $(this).children().length === 0 && $.trim($(this).text()) !== '';
                }).first();

                if (!$label.length) $label = $el;

                if (!$el.find('.ose-archived-badge').length) {
                    $el.append('<span class="ose-archived-badge" style="display:block;font-size:10px;color:#aaa;font-style:normal;line-height:1.2;margin-top:2px;">archived</span>');
                }
            });
        }

        // À chaque réponse AJAX (ACFE charge le picker via AJAX)
        $(document).on('ajaxComplete', function() {
            setTimeout(styleArchived, 50);
        });

        // MutationObserver sur le body entier pour ne rien rater
        new MutationObserver(function() {
            styleArchived();
        }).observe(document.body, { childList: true, subtree: true });

        styleArchived();
    });
    </script>
    <?php
} );
