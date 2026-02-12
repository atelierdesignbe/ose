<?php
/**
 * Vite Integration for WordPress
 */

if (!function_exists('atelierdesign_is_vite_development')) {
    /**
     * Détecte automatiquement si le serveur Vite est actif
     * @return bool TRUE si yarn dev tourne, FALSE sinon
     */
    function atelierdesign_is_vite_development() {
        // Cache la vérification pour 5 secondes (performance)
        $flag_file = get_template_directory() . '/.vite-dev-mode';
        return file_exists($flag_file);
    }
}

if (!function_exists('atelierdesign_enqueue_vite_assets')) {
    function atelierdesign_enqueue_vite_assets() {
        $theme_dir = get_template_directory();
        $theme_uri = get_template_directory_uri();
        
        $is_dev = atelierdesign_is_vite_development();
        
        if ($is_dev) {
            // ============================================
            // MODE DÉVELOPPEMENT
            // ============================================
            
            // ✅ Utiliser localhost (accessible depuis le navigateur)
            $vite_url = 'http://localhost:5173';
            
            wp_enqueue_script(
                'vite-client',
                $vite_url . '/@vite/client',
                [],
                null,
                false
            );
            
            wp_enqueue_script(
                'atelierdesign-app',
                $vite_url . '/src/app.js',
                [],
                null,
                true
            );
            
            add_filter('script_loader_tag', function($tag, $handle) {
                if (in_array($handle, ['vite-client', 'atelierdesign-app'])) {
                    return str_replace('<script ', '<script type="module" ', $tag);
                }
                return $tag;
            }, 10, 2);
            
        } else {
            // ============================================
            // MODE PRODUCTION
            // ============================================
            
            $manifest_path = $theme_dir . '/dist/manifest.json';
            
            if (!file_exists($manifest_path)) {
                if (is_admin() && current_user_can('manage_options')) {
                    add_action('admin_notices', function() {
                        ?>
                        <div class="notice notice-warning is-dismissible">
                            <p><strong>⚠️ Atelier Design :</strong> Assets Vite non buildés. Lancez <code>yarn build</code>.</p>
                        </div>
                        <?php
                    });
                }
                return;
            }
            
            $manifest_content = @file_get_contents($manifest_path);
            if (!$manifest_content) {
                return;
            }
            
            $manifest = json_decode($manifest_content, true);
            if (!$manifest || !isset($manifest['src/app.js'])) {
                return;
            }
            
            $entry = $manifest['src/app.js'];
            
            if (isset($entry['file'])) {
                wp_enqueue_script(
                    'atelierdesign-app',
                    $theme_uri . '/dist/' . $entry['file'],
                    [],
                    null,
                    true
                );
            }
            
            if (isset($entry['css']) && is_array($entry['css'])) {
                foreach ($entry['css'] as $i => $css_file) {
                    wp_enqueue_style(
                        'atelierdesign-app-css-' . $i,
                        $theme_uri . '/dist/' . $css_file,
                        [],
                        null
                    );
                }
            }
        }

        wp_localize_script('atelierdesign-app', 'ajax', [
          'url' => admin_url('admin-ajax.php'),
          'nonce' => wp_create_nonce('my_nonce')
        ]);
    }
}

add_action('wp_enqueue_scripts', 'atelierdesign_enqueue_vite_assets');

add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $is_dev = atelierdesign_is_vite_development();
    
    $wp_admin_bar->add_node([
        'id' => 'vite-mode',
        'title' => $is_dev ? '🔥 MODE DEV' : '🚀 MODE PROD',
        'meta' => [
            'title' => $is_dev 
                ? 'Hot Reload actif - yarn dev tourne' 
                : 'Assets buildés - yarn dev arrêté',
        ],
    ]);
}, 100);
