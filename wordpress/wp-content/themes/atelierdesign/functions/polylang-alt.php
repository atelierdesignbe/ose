<?php

defined('ABSPATH') || exit;
 
// ─────────────────────────────────────────────────────────────────────────────
// 1. VÉRIFICATION DES DÉPENDANCES
// ─────────────────────────────────────────────────────────────────────────────
 
/**
 * Retourne true si ACF Pro et Polylang sont tous les deux actifs.
 */
function pll_acf_alt_ok(): bool {
    return function_exists('acf_add_local_field_group')
        && function_exists('pll_languages_list');
}
 
// ─────────────────────────────────────────────────────────────────────────────
// 2. RÉCUPÉRATION DES LANGUES POLYLANG (dynamique, pas hardcodé)
// ─────────────────────────────────────────────────────────────────────────────
 
/**
 * Récupère toutes les langues actives Polylang avec slug, nom et drapeau.
 * Compatible Polylang free et Pro.
 *
 * @return array[] [ ['slug' => 'fr', 'name' => 'Français', 'flag_url' => '...'], ... ]
 */
function pll_acf_alt_get_languages(): array {
    if ( ! function_exists('pll_languages_list') ) {
        return [];
    }
 
    $languages = [];
 
    // Méthode principale : objet global PLL (disponible après plugins_loaded)
    if ( function_exists('PLL') && PLL() && method_exists(PLL()->model, 'get_languages_list') ) {
        foreach ( PLL()->model->get_languages_list() as $lang ) {
          $languages[] = [
              'slug'     => $lang->slug,
              'name'     => $lang->name,
              'flag_url' => function_exists('pll_get_flag_url')
                  ? pll_get_flag_url($lang->slug)
                  : '',
          ];
      }
        return $languages;
    }
 
    // Fallback : slugs uniquement (Polylang très ancien ou config inhabituelle)
    foreach ( pll_languages_list() as $slug ) {
        $languages[] = [
            'slug'     => $slug,
            'name'     => strtoupper( $slug ),
            'flag_url' => '',
        ];
    }
 
    return $languages;
}
 
// ─────────────────────────────────────────────────────────────────────────────
// 3. ENREGISTREMENT DU GROUPE ACF SUR LES MÉDIAS
// ─────────────────────────────────────────────────────────────────────────────
 
add_action('acf/init', function () {
    if ( ! pll_acf_alt_ok() ) {
        return;
    }
 
    $languages = pll_acf_alt_get_languages();
 
    if ( empty($languages) ) {
        return;
    }
 
    $fields = [];
 
    foreach ( $languages as $lang ) {
        // Construit le libellé avec drapeau (affiché dans instructions car ACF échappe les labels)
        $flag_html = '';
        if ( ! empty($lang['flag_url']) ) {
            $flag_html = '<img src="' . esc_url($lang['flag_url']) . '" '
                       . 'style="height:14px;vertical-align:middle;margin-right:5px;" '
                       . 'alt="' . esc_attr($lang['slug']) . '"> ';
        }

        $flag_emoji_map = [
          'fr' => '🇧🇪',
          'en' => '🇬🇧',
          'nl' => '🇳🇱',
      ];
      
    
        $fields[] = [
            'key'                   => 'field_pll_alt_' . $lang['slug'],
            // Label visible au-dessus du champ (texte brut + slug en majuscule)
            'label'                 =>   $flag_emoji_map[$lang['slug']]. ' ' .$lang['name'],
            // Nom du meta enregistré en base : pll_alt_fr, pll_alt_nl, etc.
            'name'                  => 'pll_alt_' . $lang['slug'],
            'type'                  => 'text',
            // Instructions : affiche le drapeau + texte explicatif (HTML autorisé ici)
            // 'instructions'          => 'Texte ALT pour la langue <strong>'
            //                          . esc_html($lang['name'])
            //                          . '</strong> (max 125 caractères recommandés)',
            // 'required'              => 0,
            'placeholder'           => 'Description de l\'image en ' . esc_attr($lang['name']),
            // 'maxlength'             => 0, 
            'wrapper' => [
              'width' => '100',
              'class' => 'pll-acf-alt-field',
          ],
        ];
    }
 
    acf_add_local_field_group([
        'key'                   => 'group_pll_media_alt',
        'title'                 => '🌐 Texte ALT multilingue (Polylang)',
        'fields'                => $fields,
        // Apparaît sur TOUS les médias (images, fichiers…)
        'location'              => [
            [
                [
                    'param'    => 'attachment',
                    'operator' => '==',
                    'value'    => 'all',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',   // drapeau juste sous le label
        'active'                => true,
        'description'           => 'Champs ALT générés automatiquement à partir des langues Polylang actives.',
    ]);
});
 
// ─────────────────────────────────────────────────────────────────────────────
// 4. INJECTION AUTOMATIQUE DU BON ALT — wp_get_attachment_image() et variantes
//    Couvre : the_post_thumbnail(), wp_get_attachment_image(), get_the_post_thumbnail()
// ─────────────────────────────────────────────────────────────────────────────
 
add_filter('wp_get_attachment_image_attributes', function (array $attr, WP_Post $attachment, $size): array {
    if ( ! function_exists('pll_current_language') ) {
        return $attr;
    }
 
    $slug = pll_current_language('slug');
 
    if ( empty($slug) ) {
        return $attr;
    }
 
    $acf_alt = get_field('pll_alt_' . $slug, $attachment->ID);
 
    if ( ! empty(trim((string) $acf_alt)) ) {
        $attr['alt'] = esc_attr(trim($acf_alt));
    }
    // Si le champ ACF est vide → on garde l'ALT natif WordPress comme fallback
 
    return $attr;
}, 10, 3);
 
// ─────────────────────────────────────────────────────────────────────────────
// 5. INJECTION VIA get_post_meta('_wp_attachment_image_alt')
//    Couvre : REST API, plugins SEO (Yoast, Rank Math), wc_get_product_image(), etc.
//    ⚠ Désactivé en admin pour ne pas perturber l'interface de la médiathèque.
// ─────────────────────────────────────────────────────────────────────────────
 
add_filter('get_post_metadata', function ($value, int $object_id, string $meta_key, bool $single) {
    // On ne touche qu'au meta ALT natif de WordPress
    if ( $meta_key !== '_wp_attachment_image_alt' ) {
        return $value;
    }
 
    // Pas en admin (on garde le comportement natif dans la médiathèque)
    if ( is_admin() ) {
        return $value;
    }
 
    if ( ! function_exists('pll_current_language') ) {
        return $value;
    }
 
    // Protection anti-boucle infinie (get_field() appelle lui-même get_post_meta)
    static $running = false;
    if ( $running ) {
        return $value;
    }
 
    $slug = pll_current_language('slug');
    if ( empty($slug) ) {
        return $value;
    }
 
    $running  = true;
    $acf_alt  = get_field('pll_alt_' . $slug, $object_id);
    $running  = false;
 
    if ( ! empty(trim((string) $acf_alt)) ) {
        // Retourne le bon format selon $single (true = string, false = array)
        return $single ? trim($acf_alt) : [ trim($acf_alt) ];
    }
 
    // Fallback : ALT WordPress natif (champ standard de la médiathèque)
    return $value;
}, 10, 4);
 
// ─────────────────────────────────────────────────────────────────────────────
// 6. (OPTIONNEL) PETIT CSS ADMIN — met en valeur les champs dans la médiathèque
// ─────────────────────────────────────────────────────────────────────────────
 
add_action('admin_head', function () {
    if ( ! is_admin() ) {
        return;
    }
    ?>
    <style>
        /* Encadre visuellement le groupe ALT multilingue dans la médiathèque */
        .pll-acf-alt-field .acf-label label {
            font-weight: 700;
            letter-spacing: .03em;
        }
        .pll-acf-alt-field .acf-input input[type="text"] {
            border-left: 3px solid #2271b1;
            padding-left: 8px;
        }
        #acf-group_pll_media_alt .acf-field-group-title {
            background: #f0f6fc;
        }
    </style>
    <?php
});