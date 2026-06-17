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
        'menu_position'      => 16,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'revisions' ),
        'taxonomies'         => array( 'themes', 'event_type', 'post_tag' )
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
        'menu_position'      => 12,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'revisions' ),
        'taxonomies'         => array( 'themes', 'types', 'post_tag' )
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
        'menu_position'      => 14,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'revisions' ),
        'taxonomies'         => array( 'themes', 'post_tag' )
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'cpt_projects');


// AUTHOR (Members)

function cpt_authors() {
    $labels = array(
      'name'               => 'Members',
      'singular_name'      => 'Member',
      'menu_name'          => 'Members',
      'name_admin_bar'     => 'Member',
      'add_new'            => 'Add New',
      'add_new_item'       => 'Add New Member',
      'new_item'           => 'New Member',
      'edit_item'          => 'Edit Member',
      'view_item'          => 'View Member',
      'all_items'          => 'All Members',
      'search_items'       => 'Search Members',
      'not_found'          => 'No Members found',
      'not_found_in_trash' => 'No Members found in Trash'
  );

    $args = array(
        'labels'              => $labels,
        'public'              => true,               // ✅ Accessible publiquement
        'publicly_queryable'  => true,               // ✅ Accès via URL
        'show_ui'             => true,               // ✅ Interface admin visible
        'show_in_menu'        => true,               // ✅ Affiché dans le menu
        'menu_icon'           => 'dashicons-businessman',
        // ⚠️ 'author' est un query_var réservé par WordPress — on utilise un nom custom
        'query_var'           => 'ose_author',
        'rewrite'             => array( 'slug' => 'team', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 18,
        'show_in_rest'        => true,
        'supports'            => array( 'title', 'revisions' ),
        'exclude_from_search' => false,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'taxonomies'          => array('member_type', 'themes', 'post_tag')
    );

    register_post_type( 'author', $args );
}
add_action( 'init', 'cpt_authors' );

function cpt_external_authors() {
  $labels = array(
    'name'               => 'External people',
    'singular_name'      => 'External people',
    'menu_name'          => 'External people',
    'name_admin_bar'     => 'External people',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New External people',
    'new_item'           => 'New External people',
    'edit_item'          => 'Edit External people',
    'view_item'          => 'View External people',
    'all_items'          => 'All External people',
    'search_items'       => 'Search External people',
    'not_found'          => 'No External people found',
    'not_found_in_trash' => 'No External people found in Trash'
  );

  $args = array(
      'labels'              => $labels,
      'public'              => false,               // ✅ Accessible publiquement
      'publicly_queryable'  => true,               // ✅ Accès via URL
      'show_ui'             => true,               // ✅ Interface admin visible
      'show_in_menu'        => true,               // ✅ Affiché dans le menu
      'menu_icon'           => 'dashicons-businessman',
      'query_var'           => 'ose_external_author',
      'rewrite'             => false,
      'capability_type'     => 'post',
      'has_archive'         => false,
      'hierarchical'        => false,
      'menu_position'       => 19,
      'show_in_rest'        => true,
      'supports'            => array( 'title' ),
      'exclude_from_search' => false,
      'show_in_nav_menus'   => false,
      'can_export'          => true,
      'taxonomies'          => array()
);

  register_post_type( 'external_author', $args );
}
add_action( 'init', 'cpt_external_authors' );

// Taxonomy: Member Type (pour le CPT author)
function custom_taxonomy_member_type() {
    $labels = array(
        'name'              => 'Member Types',
        'singular_name'     => 'Member Type',
        'search_items'      => 'Search Member Types',
        'all_items'         => 'All Member Types',
        'edit_item'         => 'Edit Member Type',
        'update_item'       => 'Update Member Type',
        'add_new_item'      => 'Add New Member Type',
        'new_item_name'     => 'New Member Type',
        'menu_name'         => 'Member Types',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,  // comme des catégories (checkbox dans l'admin)
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'member-type'),
    );

    register_taxonomy('member_type', array('author'), $args);
}
add_action('init', 'custom_taxonomy_member_type');

/**
 * Meta box radio button pour member_type
 *
 * Remplace la meta box checkbox par défaut par une sélection unique (radio).
 * Un membre ne peut appartenir qu'à un seul type à la fois.
 */

// 1. Supprimer la meta box checkbox par défaut
add_action('add_meta_boxes', function () {
    remove_meta_box('member_typediv', 'author', 'side');
});

// 2. Ajouter une meta box avec radio buttons
add_action('add_meta_boxes', function () {
    add_meta_box(
        'member_type_radio',
        'Member Type',
        function ($post) {
            $terms        = get_terms(['taxonomy' => 'member_type', 'hide_empty' => false, 'orderby' => 'name']);
            $current_ids  = wp_get_object_terms($post->ID, 'member_type', ['fields' => 'ids']);
            $current_id   = !empty($current_ids) ? (int) $current_ids[0] : 0;

            wp_nonce_field('member_type_radio_save', 'member_type_radio_nonce');

            echo '<div style="display:flex;flex-direction:column;gap:6px;padding:4px 0;">';

            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">';
                    echo '<input type="radio" name="member_type_radio" value="' . esc_attr($term->term_id) . '" '
                        . checked((int) $term->term_id, $current_id, false) . '>';
                    echo esc_html($term->name);
                    echo '</label>';
                }
            }

            echo '</div>';
        },
        'author',
        'side',
        'default'
    );
});

// 3. Sauvegarder la sélection
add_action('save_post_author', function ($post_id) {
    if (
        ! isset($_POST['member_type_radio_nonce']) ||
        ! wp_verify_nonce($_POST['member_type_radio_nonce'], 'member_type_radio_save')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (! current_user_can('edit_post', $post_id))    return;

    $term_id = isset($_POST['member_type_radio']) ? (int) $_POST['member_type_radio'] : 0;

    // wp_set_object_terms avec false = remplace toute la liste (= sélection unique)
    wp_set_object_terms($post_id, $term_id ? [$term_id] : [], 'member_type', false);
});


/**
 * Meta box radio button pour la taxonomie "types" sur Publication
 * Sélection unique — un seul type par publication.
 */
add_action('add_meta_boxes', function () {
    remove_meta_box('typesdiv', 'publication', 'side');
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'publication_type_radio',
        'Type',
        function ($post) {
            $terms       = get_terms(['taxonomy' => 'types', 'hide_empty' => false, 'orderby' => 'name']);
            $current_ids = wp_get_object_terms($post->ID, 'types', ['fields' => 'ids']);
            $current_id  = !empty($current_ids) ? (int) $current_ids[0] : 0;

            wp_nonce_field('publication_type_radio_save', 'publication_type_radio_nonce');

            echo '<div style="display:flex;flex-direction:column;gap:6px;padding:4px 0;">';

            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">';
                    echo '<input type="radio" name="publication_type_radio" value="' . esc_attr($term->term_id) . '" '
                        . checked((int) $term->term_id, $current_id, false) . '>';
                    echo esc_html($term->name);
                    echo '</label>';
                }
            }

            echo '</div>';
        },
        'publication',
        'side',
        'default'
    );
});

add_action('save_post_publication', function ($post_id) {
    if (
        ! isset($_POST['publication_type_radio_nonce']) ||
        ! wp_verify_nonce($_POST['publication_type_radio_nonce'], 'publication_type_radio_save')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (! current_user_can('edit_post', $post_id))    return;

    $term_id = isset($_POST['publication_type_radio']) ? (int) $_POST['publication_type_radio'] : 0;

    wp_set_object_terms($post_id, $term_id ? [$term_id] : [], 'types', false);
});


// Seed des termes par défaut (exécuté une seule fois via versioning)
add_action('init', function () {
    $version = '1.0';
    if (get_option('ose_member_type_terms_version') !== $version) {
        $default_terms = ['Team OSE', 'Research Team', 'Administrative Team', 'Research Associate'];
        foreach ($default_terms as $term) {
            if (!term_exists($term, 'member_type')) {
                wp_insert_term($term, 'member_type');
            }
        }
        update_option('ose_member_type_terms_version', $version);
    }
}, 20); // priorité 20 → après l'enregistrement de la taxonomie (priorité défaut 10)


// =============================================================================
// Taxonomy: Member Status (Active / Archived)
// =============================================================================

function custom_taxonomy_member_status() {
    register_taxonomy( 'member_status', [ 'author' ], [
        'labels'            => [
            'name'          => 'Member Status',
            'singular_name' => 'Status',
            'menu_name'     => 'Status',
            'all_items'     => 'All Statuses',
            'edit_item'     => 'Edit Status',
            'add_new_item'  => 'Add New Status',
        ],
        'hierarchical'      => false,
        'public'            => false,   // pas d'URL publique
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => false,
        'rewrite'           => false,
    ] );
}
add_action( 'init', 'custom_taxonomy_member_status' );

// Seed Active / Archived (une seule fois)
add_action( 'init', function () {
    $version = '1.0';
    if ( get_option( 'ose_member_status_terms_version' ) !== $version ) {
        foreach ( [ 'Active' => 'active', 'Archived' => 'archived' ] as $name => $slug ) {
            if ( ! term_exists( $slug, 'member_status' ) ) {
                wp_insert_term( $name, 'member_status', [ 'slug' => $slug ] );
            }
        }
        update_option( 'ose_member_status_terms_version', $version );
    }
}, 20 );

// Supprimer la meta box native de la taxonomie member_status — remplacée par le toggle ACF.
add_action( 'add_meta_boxes', function () {
    remove_meta_box( 'member_statusdiv', 'author', 'side' );
    remove_meta_box( 'member_statusdiv', 'author', 'normal' );
} );

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

    register_taxonomy( 'themes', array( 'publication', 'project', 'author', 'event' ), $args );
}
add_action( 'init', 'custom_taxonomy_themes' );

// Taxonomy: Event Type
function custom_taxonomy_event_type() {
    $labels = array(
        'name'              => 'Event Types',
        'singular_name'     => 'Event Type',
        'search_items'      => 'Search Event Types',
        'all_items'         => 'All Event Types',
        'edit_item'         => 'Edit Event Type',
        'update_item'       => 'Update Event Type',
        'add_new_item'      => 'Add New Event Type',
        'new_item_name'     => 'New Event Type',
        'menu_name'         => 'Event Types',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'event-type'),
    );

    register_taxonomy('event_type', array('event'), $args);
}
add_action('init', 'custom_taxonomy_event_type');

/**
 * Meta box radio button pour event_type sur Event
 * Sélection unique — un seul type par event.
 */
add_action('add_meta_boxes', function () {
    remove_meta_box('event_typediv', 'event', 'side');
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'event_type_radio',
        'Event Type',
        function ($post) {
            $terms       = get_terms(['taxonomy' => 'event_type', 'hide_empty' => false, 'orderby' => 'name']);
            $current_ids = wp_get_object_terms($post->ID, 'event_type', ['fields' => 'ids']);
            $current_id  = !empty($current_ids) ? (int) $current_ids[0] : 0;

            wp_nonce_field('event_type_radio_save', 'event_type_radio_nonce');

            echo '<div style="display:flex;flex-direction:column;gap:6px;padding:4px 0;">';

            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">';
                    echo '<input type="radio" name="event_type_radio" value="' . esc_attr($term->term_id) . '" '
                        . checked((int) $term->term_id, $current_id, false) . '>';
                    echo esc_html($term->name);
                    echo '</label>';
                }
            }

            echo '</div>';
        },
        'event',
        'side',
        'default'
    );
});

add_action('save_post_event', function ($post_id) {
    if (
        ! isset($_POST['event_type_radio_nonce']) ||
        ! wp_verify_nonce($_POST['event_type_radio_nonce'], 'event_type_radio_save')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (! current_user_can('edit_post', $post_id))    return;

    $term_id = isset($_POST['event_type_radio']) ? (int) $_POST['event_type_radio'] : 0;

    wp_set_object_terms($post_id, $term_id ? [$term_id] : [], 'event_type', false);
});

// Seed des termes event_type par défaut
add_action('init', function () {
    $version = '1.0';
    if (get_option('ose_event_type_terms_version') !== $version) {
        $default_terms = [
            'Afterwork discussion',
            'Workshop',
            'Webinar',
            'Seminar',
            'Roundtable discussion',
            'OSE afterwork discussion',
            'Event',
            'Conference',
        ];
        foreach ($default_terms as $term) {
            if (!term_exists($term, 'event_type')) {
                wp_insert_term($term, 'event_type');
            }
        }
        update_option('ose_event_type_terms_version', $version);
    }
}, 20);

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

    register_taxonomy( 'types', array( 'publication' ), $args );
}
add_action( 'init', 'custom_taxonomy_types' );

// Taxonomy: PROJECT
// function custom_taxonomy_projects() {
//     $labels = array(
//         'name'              => 'Projects',
//         'singular_name'     => 'Project',
//         'search_items'      => 'Search Projects',
//         'all_items'         => 'All Projects',
//         'parent_item'       => 'Parent Project',
//         'parent_item_colon' => 'Parent Project:',
//         'edit_item'         => 'Edit Project',
//         'update_item'       => 'Update Project',
//         'add_new_item'      => 'Add New Project',
//         'new_item_name'     => 'New Project Name',
//         'menu_name'         => 'Projects',
//     );

//     $args = array(
//         'labels'            => $labels,
//         'hierarchical'      => true,
//         'public'            => true,
//         'show_ui'           => true,
//         'show_admin_column' => true,
//         'show_in_nav_menus' => true,
//         'show_tagcloud'     => true,
//         'show_in_rest'      => true,
//         'rewrite'           => array( 'slug' => 'projects' ),
//     );

//     register_taxonomy( 'projects', array( 'publication' ), $args );
// }
// add_action( 'init', 'custom_taxonomy_projects' );
