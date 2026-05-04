<?php



// ── Validation du champ link selon le type ─────────────────────────────────────
//
// ACF valide 'required' sur tous les champs, même cachés par conditional_logic.
// On retire donc 'required' du champ link et on gère la validation ici :
// le lien n'est obligatoire que quand type === 'link' (ou quand il n'y a pas
// de toggle de type, i.e. au niveau max de profondeur).
//
// Helper partagé entre menu & menu-top — défini une seule fois.
if ( ! function_exists( 'adwp_read_post_path' ) ) {
  /**
   * Lit une valeur dans $_POST à partir d'un input_name ACF.
   * Ex. : "acf[field-menu-items][0][field-menu-l0-type]"
   *       → $_POST['acf']['field-menu-items']['0']['field-menu-l0-type']
   */
  function adwp_read_post_path( string $input_name ) {
    if ( ! preg_match( '/^([^\[]+)(.*)$/', $input_name, $m ) ) return null;
    $keys = [ $m[1] ];
    if ( preg_match_all( '/\[([^\]]*)\]/', $m[2], $b ) ) {
      $keys = array_merge( $keys, $b[1] );
    }
    $v = $_POST;
    foreach ( $keys as $k ) {
      if ( ! is_array( $v ) || ! array_key_exists( $k, $v ) ) return null;
      $v = $v[ $k ];
    }
    return $v;
  }
}

add_filter( 'acf/validate_value', function ( $valid, $value, $field, $input_name ) use ( $fieldKey ) {

  // Cible uniquement nos champs link : {fieldKey}-l{n}-link
  if ( ! preg_match( '/^' . preg_quote( $fieldKey, '/' ) . '-l(\d+)-link$/', $field['key'], $m ) ) {
    return $valid;
  }

  $level    = (int) $m[1];
  $type_key = $fieldKey . '-l' . $level . '-type';

  // Remplace la clé du champ link par celle du champ type dans le chemin POST
  $type_path = str_replace( '[' . $field['key'] . ']', '[' . $type_key . ']', $input_name );
  $type      = adwp_read_post_path( $type_path );

  // Valide seulement si type === 'link' (ou pas de toggle = niveau feuille)
  if ( $type === null || $type === 'link' ) {
    if ( empty( $value ) || ( is_array( $value ) && empty( $value['url'] ) ) ) {
      return __( 'Le champ Lien est obligatoire.', 'atelierdesign' );
    }
  }

  return $valid;
}, 10, 4 );

// ── Admin assets ───────────────────────────────────────────────────────────────
// Loaded only on ACF edit screens to avoid unnecessary overhead.

add_action('admin_enqueue_scripts', function () {
    $base    = get_template_directory_uri() . '/components/menu/';
    $version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
      'menu-admin',
      $base . 'menu-admin.css',
      [],
      $version
    );

    wp_enqueue_script(
      'menu-admin',
      $base . 'menu-admin.js',
      [ 'acf-input' ],
      $version,
      true
    );
});