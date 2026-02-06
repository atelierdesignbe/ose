<?php
function unregistered_categories() {
    unregister_taxonomy_for_object_type( 'category', 'post' );
}
add_action( 'init', 'unregistered_categories' );

// Events
function cpt_events() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'name_admin_bar'     => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'new_item'           => 'New Event',
        'edit_item'          => 'Edit Event',
        'view_item'          => 'View Event',
        'all_items'          => 'All Events',
        'search_items'       => 'Search Events',
        'not_found'          => 'No Events found',
        'not_found_in_trash' => 'No Events found in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'events', 'with_front' => false  ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( )
    );

    register_post_type( 'event', $args );
}
add_action( 'init', 'cpt_events');

// Publications
function cpt_publications() {
    $labels = array(
        'name'               => 'Publications',
        'singular_name'      => 'Publication',
        'menu_name'          => 'Publications',
        'name_admin_bar'     => 'Publication',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Publication',
        'new_item'           => 'New Publication',
        'edit_item'          => 'Edit Publication',
        'view_item'          => 'View Publication',
        'all_items'          => 'All Publications',
        'search_items'       => 'Search Publications',
        'not_found'          => 'No Publications found',
        'not_found_in_trash' => 'No Publications found in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-book-alt',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'publications', 'with_front' => false  ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'themes', 'types' )
    );

    register_post_type( 'publication', $args );
}
add_action( 'init', 'cpt_publications');

// Projects
function cpt_projects() {
    $labels = array(
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'menu_name'          => 'Projects',
        'name_admin_bar'     => 'Project',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'new_item'           => 'New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No Projects found',
        'not_found_in_trash' => 'No Projects found in Trash'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-lightbulb',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects', 'with_front' => false  ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 23,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'themes', 'types' )
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'cpt_projects');


// AUTHOR

function cpt_authors() {
    $labels = array(
        'name'               => 'Authors',
        'singular_name'      => 'Author',
        'menu_name'          => 'Authors',
        'name_admin_bar'     => 'Author',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Author',
        'new_item'           => 'New Author',
        'edit_item'          => 'Edit Author',
        'view_item'          => 'View Author',
        'all_items'          => 'All Authors',
        'search_items'       => 'Search Authors',
        'not_found'          => 'No Authors found',
        'not_found_in_trash' => 'No Authors found in Trash'
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => false,              // ❌ Pas accessible publiquement
        'publicly_queryable'  => false,              // ❌ Pas d'accès via URL
        'show_ui'             => true,               // ✅ Interface admin visible
        'show_in_menu'        => true,               // ✅ Affiché dans le menu
        'menu_icon'           => 'dashicons-businessman', // Icône représentant un auteur
        'query_var'           => false,              // ❌ Pas de query var
        'rewrite'             => false,              // ❌ Pas de réécriture URL
        'capability_type'     => 'post',
        'has_archive'         => false,              // ❌ Pas de page d'archive
        'hierarchical'        => false,
        'menu_position'       => 22,                 // Position après Events
        'show_in_rest'        => true,               // ✅ Actif pour Gutenberg/ACF
        'supports'            => array( 'title', 'thumbnail' ), // Titre + Image seulement
        'exclude_from_search' => true,               // ❌ Exclu des recherches
        'show_in_nav_menus'   => false,              // ❌ Pas dans les menus de navigation
        'can_export'          => true,               // ✅ Peut être exporté
        'taxonomies'          => array()
    );
    
    register_post_type( 'author', $args );
}
add_action( 'init', 'cpt_authors' );

// function create_taxonomies() {
//     // Services
//   register_taxonomy( 'services', array('post', 'case_study'), array(
//       'labels' => array(
//           'name'              => 'Services',
//           'singular_name'     => 'Service',
//           'search_items'      => 'Search Services',
//           'all_items'         => 'All Services',
//           'edit_item'         => 'Edit Service',
//           'update_item'       => 'Update Service',
//           'add_new_item'      => 'Add New Service',
//           'new_item_name'     => 'New Service',
//           'menu_name'         => 'Services'
//       ),
//       'hierarchical' => true, // Like categories
//       'show_ui'       => true,
//       'show_in_rest'  => true, // Gutenberg / REST API
//       'rewrite'       => array('slug' => 'service-category')
//   ));

//   // Expertises
//   register_taxonomy( 'expertises', array('post', 'case_study'), array(
//       'labels' => array(
//           'name'              => 'Expertises',
//           'singular_name'     => 'Expertise',
//           'search_items'      => 'Search Expertises',
//           'all_items'         => 'All Expertises',
//           'edit_item'         => 'Edit Expertise',
//           'update_item'       => 'Update Expertise',
//           'add_new_item'      => 'Add New Expertise',
//           'new_item_name'     => 'New Expertise',
//           'menu_name'         => 'Expertises'
//       ),
//       'hierarchical' => true,
//       'show_ui'       => true,
//       'show_in_rest'  => true,
//   ));

//   // Subjects
//   register_taxonomy( 'subjects', 'post', array(
//       'labels' => array(
//           'name'              => 'Subjects',
//           'singular_name'     => 'Subject',
//           'search_items'      => 'Search Subjects',
//           'all_items'         => 'All Subjects',
//           'edit_item'         => 'Edit Subject',
//           'update_item'       => 'Update Subject',
//           'add_new_item'      => 'Add New Subject',
//           'new_item_name'     => 'New Subject',
//           'menu_name'         => 'Subjects'
//       ),
//       'hierarchical' => true,
//       'show_ui'       => true,
//       'show_in_rest'  => true,
//   ));
// }

// add_action( 'init', 'create_taxonomies' );

/**
 * CUSTOM TAX
 */

 // Taxonomy: Themes
function custom_taxonomy_themes() {
    $labels = array(
        'name'              => 'Themes',
        'singular_name'     => 'Theme',
        'search_items'      => 'Search Themes',
        'all_items'         => 'All Themes',
        'parent_item'       => 'Parent Theme',
        'parent_item_colon' => 'Parent Theme:',
        'edit_item'         => 'Edit Theme',
        'update_item'       => 'Update Theme',
        'add_new_item'      => 'Add New Theme',
        'new_item_name'     => 'New Theme Name',
        'menu_name'         => 'Themes',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true, // true = comme les catégories, false = comme les tags
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true, // Gutenberg compatible
        'rewrite'           => array( 'slug' => 'themes' ),
    );

    register_taxonomy( 'themes', array( 'publication', 'project' ), $args );
}
add_action( 'init', 'custom_taxonomy_themes' );

// Taxonomy: Types
function custom_taxonomy_types() {
    $labels = array(
        'name'              => 'Types',
        'singular_name'     => 'Type',
        'search_items'      => 'Search Types',
        'all_items'         => 'All Types',
        'parent_item'       => 'Parent Type',
        'parent_item_colon' => 'Parent Type:',
        'edit_item'         => 'Edit Type',
        'update_item'       => 'Update Type',
        'add_new_item'      => 'Add New Type',
        'new_item_name'     => 'New Type Name',
        'menu_name'         => 'Types',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'types' ),
    );

    register_taxonomy( 'types', array( 'publication', 'project' ), $args );
}
add_action( 'init', 'custom_taxonomy_types' );