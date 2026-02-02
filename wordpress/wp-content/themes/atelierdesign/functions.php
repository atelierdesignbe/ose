<?php

/**
 * AD UI
 */

 
// Core functions & styles
$ui_dir = get_template_directory() . '/ui';
$ui_init_file = $ui_dir . '/wp/init.php';

if (file_exists($ui_init_file)) {

  require_once 'ui/wp/init.php';

  $adwp = new ADWP([
    'packageFolder' => get_template_directory() . '/ui',
    'packageUrl' => get_template_directory_uri() . '/ui',
    'customFlexibleComponentPath' => get_template_directory() . '/components',
    'disableEnqueueStyles' => true,
  ]);

  // Advanced Custom Fields modules
  require_once 'ui/acf/includes.php';
} else {
   // UI n'est pas installé - Afficher un avertissement dans l'admin
    add_action('admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p>
                <strong>⚠️ Atelier Design Theme :</strong> 
                Le dossier <code>/ui</code> n'est pas installé.
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
                <p>Le dossier <code>/ui</code> n\'est pas installé.</p>
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

  $bugherd_api_key = 'xxvwkcu0vexiwpftkcudlq';
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