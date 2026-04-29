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
