<?php

/**
 * AD UI
 */
// @ini_set('display_errors', 1);
// @ini_set('display_startup_errors', 1);
// @error_reporting(E_ALL);

// // Forcer l'affichage même si WordPress le désactive
// add_action('init', function() {
//     ini_set('display_errors', 1);
//     error_reporting(E_ALL);
// });

// Core functions & styles
$ui_dir = get_template_directory() . '/ad-ui';
$ui_init_file = $ui_dir . '/wp/init.php';

if (file_exists($ui_init_file)) {

  require_once 'ad-ui/wp/init.php';

  // Ensure $adwp binds to the true global, not the enclosing scope.
  // WP-CLI requires wp-settings.php from inside a method, so functions.php
  // runs in method scope — without this, $adwp is null in fields.php/markup.php.
  global $adwp;
  $adwp = new ADWP([
    'packageFolder' => get_template_directory() . '/ad-ui',
    'packageUrl' => get_template_directory_uri() . '/ad-ui',
    'customFlexibleComponentPath' => get_template_directory() . '/components',
    'disableEnqueueStyles' => true,
  ]);

  // Advanced Custom Fields modules
  require_once 'ad-ui/acf/includes.php';
} else {
   // UI n'est pas installé - Afficher un avertissement dans l'admin
    add_action('admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong>⚠️ Atelier Design Theme :</strong> 
                Le dossier <code>/ad-ui</code> n'est pas installé.
            </p>
            <p>
                Pour installer le thème, lancez les commandes suivantes dans le terminal à la racine du projet:
            </p>
            <pre style="background: #f0f0f0; padding: 10px; border-radius: 4px;"> yarn postinstall ou yarn ui:init </pre>
        </div>
        <?php
    });
    
    // Empêcher l'affichage du site en frontend
    add_action('template_redirect', function() {
        if (!is_admin()) {
            wp_die(
                '<h1>Thème non configuré</h1>
                <p>Le dossier <code>/ad-ui</code> n\'est pas installé.</p>
                <p>Veuillez lancer <code>yarn install</code> dans le dossier du thème.</p>
                <hr>
                <p><a href="' . admin_url() . '">← Retour à l\'admin</a></p>',
                'Thème non configuré',
                ['response' => 503]
            );
        }
    });
}

function atelierdesign_required_plugins() {
    return [
        'advanced-custom-fields-pro/acf.php' => 'Advanced Custom Fields PRO',
        'acf-extended-pro/acf-extended.php' => 'ACF Extended Pro',
        'formidable/formidable.php' => 'Formidable Forms',
        'formidable-pro/formidable-pro.php' => 'Formidable Forms Pro',
        // 'polylang/polylang.php' => 'Polylang',
    ];
}

/**
 * Vérifier quels plugins sont manquants
 */
function atelierdesign_get_missing_plugins() {
    $required = atelierdesign_required_plugins();
    $missing = [];
    
    foreach ($required as $plugin_path => $plugin_name) {
        // Vérifier si le plugin existe
        if (!file_exists(WP_PLUGIN_DIR . '/' . $plugin_path)) {
            $missing[] = [
                'name' => $plugin_name,
                'path' => $plugin_path,
                'status' => 'not_installed'
            ];
            continue;
        }
        
        // Vérifier si le plugin est actif
        if (!is_plugin_active($plugin_path)) {
            $missing[] = [
                'name' => $plugin_name,
                'path' => $plugin_path,
                'status' => 'inactive'
            ];
        }
    }
    
    return $missing;
}

/**
 * Activer les plugins manquants
 */
function atelierdesign_activate_missing_plugins() {
    // Vérifier le nonce
    if (!isset($_GET['atelierdesign_activate_plugins']) || 
        !wp_verify_nonce($_GET['_wpnonce'], 'atelierdesign_activate_plugins')) {
        return;
    }
    
    if (!current_user_can('activate_plugins')) {
        return;
    }
    
    $missing = atelierdesign_get_missing_plugins();
    $activated = [];
    $errors = [];
    
    foreach ($missing as $plugin) {
        // Ne traiter que les plugins inactifs (pas ceux non installés)
        if ($plugin['status'] !== 'inactive') {
            continue;
        }
        
        $result = activate_plugin($plugin['path'], '', false, true);
        
        if (is_wp_error($result)) {
            $errors[] = $plugin['name'];
        } else {
            $activated[] = $plugin['name'];
        }
    }
    
    // Stocker les résultats
    if (!empty($activated)) {
        set_transient('atelierdesign_activated_plugins', $activated, 30);
    }
    
    if (!empty($errors)) {
        set_transient('atelierdesign_activation_errors', $errors, 30);
    }
    
    // Rediriger pour éviter la réexécution
    wp_redirect(admin_url('themes.php'));
    exit;
}
add_action('admin_init', 'atelierdesign_activate_missing_plugins');

/**
 * Afficher la notice pour les plugins manquants
 */
function atelierdesign_missing_plugins_notice() {
    $missing = atelierdesign_get_missing_plugins();
    
    // Plugins activés avec succès
    $activated = get_transient('atelierdesign_activated_plugins');
    if ($activated) {
        delete_transient('atelierdesign_activated_plugins');
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✅ Plugins activés avec succès :</strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ($activated as $plugin): ?>
                    <li><?php echo esc_html($plugin); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
    
    // Erreurs d'activation
    $errors = get_transient('atelierdesign_activation_errors');
    if ($errors) {
        delete_transient('atelierdesign_activation_errors');
        ?>
        <div class="notice notice-error is-dismissible">
            <p><strong>❌ Erreurs lors de l'activation :</strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ($errors as $plugin): ?>
                    <li><?php echo esc_html($plugin); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
    
    // Si tous les plugins sont actifs, ne rien afficher
    if (empty($missing)) {
        return;
    }
    
    // Séparer les plugins non installés des inactifs
    $not_installed = array_filter($missing, function($p) { return $p['status'] === 'not_installed'; });
    $inactive = array_filter($missing, function($p) { return $p['status'] === 'inactive'; });
    
    ?>
    <div class="notice notice-warning">
        <h3>⚠️ Atelier Design Theme : Plugins requis</h3>
        
        <?php if (!empty($not_installed)): ?>
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 10px 0;">
                <p><strong>Plugins non installés :</strong></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <?php foreach ($not_installed as $plugin): ?>
                        <li><?php echo esc_html($plugin['name']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>
                    💡 Lancez <code>./install-plugins.sh</code> à la racine du projet pour installer les plugins.
                </p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($inactive)): ?>
            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 10px; margin: 10px 0;">
                <p><strong>Plugins installés mais inactifs :</strong></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <?php foreach ($inactive as $plugin): ?>
                        <li><?php echo esc_html($plugin['name']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>
                    <a href="<?php echo wp_nonce_url(admin_url('themes.php?atelierdesign_activate_plugins=1'), 'atelierdesign_activate_plugins'); ?>" 
                       class="button button-primary">
                        🔌 Activer ces plugins maintenant
                    </a>
                </p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
add_action('admin_notices', 'atelierdesign_missing_plugins_notice');

require_once get_template_directory() . '/functions/vite.php';

/**
 * CUSTOM
 */

// Enqueue project specific app.js & app.css from dist folder
// add_action('wp_enqueue_scripts', function () {
//   wp_enqueue_script('app', get_template_directory_uri() . '/dist/app.js', [], false, true);
//   wp_enqueue_style('app', get_template_directory_uri() . '/dist/app.css', [], false, 'all');
// }, PHP_INT_MAX);

// Enqueue script with BugHerd's API key in the <head> and as an async script
add_action('wp_enqueue_scripts', function () {
  if (isset($_GET['preview']) && $_GET['preview'] === 'true') return; // Skip if preview mode is active

  $bugherd_api_key = '4tnquyad55druh3ooehkta';
  wp_enqueue_script('bugherd', "https://www.bugherd.com/sidebarv2.js?apikey=$bugherd_api_key", [], false, false);
}, 1);

// Add async attribute to BugHerd's script
function add_async_attribute($tag, $handle)
{
  if ('bugherd' === $handle) { // Replace 'your-script-handle' with the handle of your script
    // $tag = str_replace(' src', ' async="true" src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

// Enqueue google font both frontend and backend
$font_url = 'https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap';
function enqueue_google_font()
{
  wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap', [], null, 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_google_font');
add_editor_style($font_url);


// Require once all the files in /src/functions/* for general functions
foreach (glob(__DIR__ . '/functions/*.php') as $file) {
  require_once $file;
}

// Require once all fields located in /src/components/**/fields.php files
if (!function_exists('acf_add_local_field_group')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-warning"><p><strong>ACF Pro required:</strong> Please install ACF Pro for this theme.</p></div>';
    });
    return;
}

// Fonction helper pour charger les fichiers
function load_acf_files($pattern) {
    foreach (glob($pattern) as $file) {
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

// Charger les fichiers
load_acf_files(__DIR__ . '/components/**/fields.php');
load_acf_files(__DIR__ . '/fieldGroups/*.php');


function setup_custom_image_sizes() {
  update_option( 'thumbnail_crop',   0 );
  update_option( 'thumbnail_size_w', 640 );
  update_option( 'thumbnail_size_h', 0 );

  // Medium → 1280px (correspond à 1280w dans le srcset)
  update_option( 'medium_size_w', 1280 );
  update_option( 'medium_size_h', 0 );

  // Large → 2560px (correspond à 2560w dans le srcset)
  update_option( 'large_size_w', 2560 );
  update_option( 'large_size_h', 0 );
}

add_action('after_setup_theme', 'setup_custom_image_sizes');

// Désactiver les tailles d'images par défaut non utilisées (optionnel)
function disable_unused_image_sizes($sizes) {
    // Garde seulement les tailles que tu utilises
    return array_intersect_key($sizes, array_flip(['thumbnail', 'medium', 'large', 'full']));
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_image_sizes');

add_filter('show_admin_bar', '__return_false');


function enqueue_fonts()
{
  wp_enqueue_style('fonts',  get_template_directory_uri() . '/fonts/fonts.css', [], null, 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_fonts');

function add_custom_editor_styles() {
  add_editor_style('fonts/fonts.css');
}
add_action('admin_init', 'add_custom_editor_styles');
// add_editor_style();


add_filter('acf/load_field/key=field-form-formidable', 'load_formidable_forms_into_select');
function load_formidable_forms_into_select($field) {

    // Réinitialiser les choix
    $field['choices'] = [];

    // Vérifier que Formidable Forms est actif
    if ( class_exists( 'FrmForm' ) ) {

        // Récupérer tous les formulaires Formidable
        $forms = FrmForm::getAll();

        // Boucler sur les formulaires pour remplir les choix
        if ( !empty($forms) ) {
            foreach ($forms as $form) {
                $field['choices'][$form->id] = $form->name;
            }
        }
    } else {
        $field['choices'][''] = '⚠️ Formidable Forms non détecté';
    }

    return $field;
}

add_action('admin_menu', 'remove_posts_menu');
function remove_posts_menu() {
  remove_menu_page('edit.php');
}

function get_term_ids_for_cpt($taxonomy, $post_types = ['post']) {
  $posts = get_posts([
    'post_type'      => $post_types,
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'post_status'    => 'publish',
  ]);

  if (empty($posts)) return [];

  return get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => true,
    'object_ids' => $posts, // Filtre uniquement les termes liés à ces posts
  ]);
}

function replace_publication_permalink_in_admin($post_link, $post) {
  if ($post->post_type !== 'publication') {
      return $post_link;
  }

  $is_external = get_field('is-external', $post->ID);
  $external_link = get_field('external-link', $post->ID);

  if ($is_external && $external_link) {
      return $external_link;
  }

  return $post_link;
}
add_filter('post_type_link', 'replace_publication_permalink_in_admin', 10, 2);


function noindex_external_publications() {
  if (!is_singular('publication')) {
      return;
  }

  $is_external = get_field('is-external');

  if ($is_external) {
      echo '<meta name="robots" content="noindex, nofollow">' . "\n";
  }
}
add_action('wp_head', 'noindex_external_publications', 1);

add_filter('acf/prepare_field/key=field-home-hero-media-image', function($field) {
  if (!is_super_admin()) {
      return false; // cache complètement le champ
  }
  return $field;
});


function add_custom_filter_rewrite_rules() {
  if (!function_exists('get_field')) return;

  $publication_link = get_field('publication-link', 'acf-options-global-fields');
  $project_link     = get_field('project-link', 'acf-options-global-fields');

  if (is_array($publication_link)) $publication_link = $publication_link['url'];
  if (is_array($project_link))     $project_link     = $project_link['url'];

  $publication_link = $publication_link ?: '/publications/';
  $project_link     = $project_link     ?: '/projects/';

  // Retire le sous-dossier WP du path (ex: /OSE/publications/ → publications)
  $wp_base = trim(parse_url(home_url(), PHP_URL_PATH), '/');

  $clean_path = function($link) use ($wp_base) {
      $path = trim(parse_url($link, PHP_URL_PATH), '/');
      if ($wp_base && str_starts_with($path, $wp_base)) {
          $path = substr($path, strlen($wp_base));
      }
      return trim($path, '/');
  };

  $publication_path = $clean_path($publication_link);
  $project_path     = $clean_path($project_link);

  $rules = [
      ['path' => $publication_path, 'filters' => ['themes', 'types', 'projects', 'authors']],
      ['path' => $project_path,     'filters' => ['themes', 'types']],
  ];

  foreach ($rules as $rule) {
      foreach ($rule['filters'] as $filter) {
          add_rewrite_rule(
              '^' . $rule['path'] . '/' . $filter . '/([^/]+)/?$',
              'index.php?pagename=' . $rule['path'] . '&active_filter_type=' . $filter . '&active_filter_value=$matches[1]',
              'top'
          );
      }
  }

  $version = '1.5';
  if (get_option('custom_rewrite_version') !== $version) {
      flush_rewrite_rules();
      update_option('custom_rewrite_version', $version);
  }
}
add_action('init', 'add_custom_filter_rewrite_rules', 99);

add_filter('query_vars', function($vars) {
  $vars[] = 'active_filter_type';
  $vars[] = 'active_filter_value';
  return $vars;
}, 1);


/**
 * @TODO
 * Add descriptioon label
 * Add 
 */
function menu_build_fields(
  string $fieldKey,
  int    $maxDepth,
  int    $level,
  bool   $isTopLevel
): array {

  $typeKey = $fieldKey . '-l' . $level . '-type';

  $fields = [];

  // ── Type toggle ────────────────────────────────────────────────────────────
  $choices = [ 'link' => 'Link' ];
  if ( $level < $maxDepth ) {
    $choices['submenu'] = 'Submenu';
  }

  if(sizeof($choices) > 1):
    $fields[] = [
      'key'           => $typeKey,
      'label'         => 'Type',
      'name'          => 'type',
      'type'          => 'button_group',
      'choices'       => $choices,
      'default_value' => 'link',
      'layout'        => 'horizontal',
      'return_format' => 'value',
      'wrapper'       => [ 'width' => $level < $maxDepth ? '50%' : '100%' ],
    ];
  endif;


  // ── Link field ─────────────────────────────────────────────────────────────

  $fields[] = [
    'key'               => $fieldKey . '-l' . $level . '-link',
    'label'             => 'Link',
    'name'              => 'link',
    'type'              => 'link',
    'conditional_logic' => [
      [ [ 'field' => $typeKey, 'operator' => '==', 'value' => 'link' ] ],
    ],
  ];

  // ── Submenu fields (only when depth allows) ────────────────────────────────
  if ( $level < $maxDepth ) {

    $fields[] = [
      'key'               => $fieldKey . '-l' . $level . '-label',
      'label'             => 'Label',
      'name'              => 'label',
      'type'              => 'text',
      
      'conditional_logic' => [
        [ [ 'field' => $typeKey, 'operator' => '==', 'value' => 'submenu' ] ],
      ],
    ];

    $fields[] = [
      'key'               => $fieldKey . '-l' . $level . '-items',
      'label'             => 'Items',
      'name'              => 'items',
      'type'              => 'flexible_content',
      'max'               => 1,
      'button_label'      => 'Add nav item',
      'layouts'           => [
        [
          'key'        => $fieldKey . '-l' . ( $level + 1 ) . '-nav-item',
          'name'       => 'nav_item',
          'label'      => 'Nav item',
          'display'    => 'block',
          'sub_fields' => menu_build_fields(
            $fieldKey,
            $maxDepth,
            $level + 1,
            false,
          ),
        ],
      ],
      'conditional_logic' => [
        [ [ 'field' => $typeKey, 'operator' => '==', 'value' => 'submenu' ] ],
      ],
    ];
  }

  return $fields;
}
// 1. Enregistrer external_author dans Polylang (évite le filtrage)
add_filter( 'pll_get_post_types', function( $post_types, $is_settings ) {
  $post_types['external_author'] = 'external_author';
  return $post_types;
}, 10, 2 );

// 2. Auto-assigner la langue par défaut au save
add_action( 'save_post_external_author', function( $post_id ) {
  if ( function_exists( 'pll_set_post_language' ) && ! pll_get_post_language( $post_id ) ) {
      pll_set_post_language( $post_id, pll_default_language() );
  }
}, 20 );

// 3. Cacher la metabox Polylang sur ce CPT
add_action( 'add_meta_boxes', function() {
  remove_meta_box( 'ml_box', 'external_author', 'side' );
}, 100 );