<?php

/**
 * Remove all WordPress branding
 */

// Remove admin footer text
function ad_remove_footer_admin()
{
  echo 'Created by <a href="https://atelierdesign.be/" target="_blank">Atelier Design</a>';
}
add_filter('admin_footer_text', 'ad_remove_footer_admin');

// Remove WordPress version from Admin Footer
function ad_remove_wp_version()
{
  return '';
}
add_filter('the_generator', 'ad_remove_wp_version');

// Remove admin footer version
function ad_remove_footer_version()
{
  return '';
}
add_filter('update_footer', 'ad_remove_footer_version', 9999);

// Remove WordPress logo from Admin Bar
function ad_remove_admin_logo()
{
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'ad_remove_admin_logo', 0);

// Remove WordPress logo from Login page
function ad_remove_wp_logo()
{
  // echo '<style type="text/css">
  //   #login h1 a { background-image: url('.get_bloginfo('template_directory').'/images/logo.png) !important; }
  // </style>';
  echo '<style type="text/css">
    #login h1 a { display: none !important; }
  </style>';
}
add_filter('login_head', 'ad_remove_wp_logo');

// Remove WordPress Events and News widget from WP Dashboard
function ad_remove_dashboard_widgets()
{
  remove_meta_box('dashboard_primary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'ad_remove_dashboard_widgets');

// Remove Welcome Panel on WP Dashboard
function ad_remove_welcome_panel()
{
  remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('load-index.php', 'ad_remove_welcome_panel');

// Remove default WordPress favicon
function ad_remove_favicon()
{
  remove_action('wp_head', 'wp_site_icon', 99);
}
add_action('wp_head', 'ad_remove_favicon');

// Remove default WordPress favicon from admin
function ad_remove_favicon_admin()
{
  remove_action('admin_head', 'wp_site_icon', 99);
}
add_action('admin_head', 'ad_remove_favicon_admin');

// Remove default WordPress favicon from login
function ad_remove_favicon_login()
{
  remove_action('login_head', 'wp_site_icon', 99);
}
add_action('login_head', 'ad_remove_favicon_login');

// Remove default WordPress favicon from admin bar
function ad_remove_favicon_admin_bar()
{
  remove_action('admin_bar_head', 'wp_site_icon', 99);
}
add_action('admin_bar_head', 'ad_remove_favicon_admin_bar');

/**
 * Hide or remove unwanted WordPress features from the admin area.
 */

// Remove Activity Widget from WP Dashboard
function ad_remove_activity_widget()
{
  remove_meta_box('dashboard_activity', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'ad_remove_activity_widget');

// Remove Quick Draft Widget from WP Dashboard
function ad_remove_quick_draft_widget()
{
  remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'ad_remove_quick_draft_widget');

/**
 * Hide update message
 * E.g.: "WordPress X.X.X is available! Please notify the site administrator."
 */
function hide_update_notice_for_non_admins()
{
  remove_action('admin_notices', 'update_nag', 3);
}
add_action('admin_head', 'hide_update_notice_for_non_admins');

/**
 * Remove help tabs
 */
function contextual_help_list_remove()
{
  global $current_screen;
  $current_screen->remove_help_tabs();
}
add_filter('contextual_help_list', 'contextual_help_list_remove');

/**
 * Remove Excerpt Learn More Link
 */

function remove_excerpt_link()
{
  echo '<style>
  .editor-post-excerpt .components-external-link {
    display: none;
  }
  </style>';
}
add_action('admin_head', 'remove_excerpt_link');

/**
 * Disable Application Passwords on WP Profiles
 */

add_filter('wp_is_application_passwords_available', '__return_false');

/**
 * Remove admin color scheme
 */
function admin_color_scheme()
{
  global $_wp_admin_css_colors;
  $_wp_admin_css_colors = array();
}
add_action('admin_head', 'admin_color_scheme');
