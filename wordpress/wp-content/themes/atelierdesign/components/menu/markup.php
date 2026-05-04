<?php
/**
 * Menu component — markup
 *
 * Appelé via get_template_part() avec $args :
 *
 *   get_template_part( 'components/menu/markup', null, [
 *     'items'     => get_field( 'items',     $source ),  // array ACF
 *     'nav_label' => get_field( 'nav_label', $source ),  // string
 *     'depth'     => 2,                                  // optionnel, écrase le token
 *   ] );
 *
 * $args prend toujours la priorité sur get_field() (fallback block / preview).
 */

global $adwp, $adui_tokens;

$_tokens_file = get_template_directory() . '/components/menu/menu.tokens.json';
$tokens       = file_exists( $_tokens_file )
  ? json_decode( file_get_contents( $_tokens_file ), true )
  : [];

$items    = $args['items']     ?? get_field( 'items' )     ?? [];
// $navLabel = $args['nav_label'] ?? get_field( 'nav_label' ) ?? __( 'Navigation', 'adwp' );
$maxDepth = $args['depth']     ?? (int) ( $tokens['default']['depth'] ?? 1 );
$chevron  = $args['chevron']   ?? '';
$mode      = $args['mode']       ?? 'dropdown'; // 'dropdown' | 'accordeon'
$depthMode = $args['depth_mode'] ?? ( $tokens['default']['depthMode'] ?? 'dropdown' ); // 'dropdown' | 'accordeon'

if ( empty( $items ) ) return;
