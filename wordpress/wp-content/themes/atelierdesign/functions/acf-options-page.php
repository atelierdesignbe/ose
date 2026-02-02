<?php

/**
 * Register ACF options page for global fields
 */

if (function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'menu_slug' => 'acf-options-global-fields',
    'page_title' => 'General Settings for Global Sections',
    'active' => true,
    'menu_title' => 'General',
    'capability' => 'edit_posts',
    'parent_slug' => '',
    'position' => '21',
    'icon_url' => 'dashicons-networking',
    'redirect' => true,
    'post_id' => 'acf-options-global-fields',
    'autoload' => false,
    'update_button' => 'Update',
    'updated_message' => 'Global Sections Updated',
    'acfe_autosync' => array(),
  ));
}
