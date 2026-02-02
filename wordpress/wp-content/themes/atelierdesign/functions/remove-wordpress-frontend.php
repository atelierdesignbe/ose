<?php

/**
 * Remove unwanted WordPress features from the frontend.
 */

// Remove WordPress.org Dns-prefetch
remove_action('wp_head', 'wp_resource_hints', 2);

// Remove Emoji Support
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Remove from TinyMCE
add_filter('tiny_mce_plugins', function ($plugins) {
  return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
});

// Disable emoji SVG URL
add_filter('emoji_svg_url', '__return_false');

// Disable speculative loading (Speculation Rules API)
add_filter('wp_preload_links', '__return_false');
add_filter('wp_speculation_rules_configuration', '__return_null');

// Remove RSS feed links
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);

// Remove WP-Embed
add_action('wp_footer', function () {
  wp_dequeue_script('wp-embed');
});

// Remove Block Library CSS, Global Styles and Comment Reply
add_action('wp_enqueue_scripts', function () {

  wp_dequeue_script('wp-block-library');
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wc-blocks-style');

  wp_dequeue_style('global-styles');

  wp_dequeue_script('comment-reply');
});
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

// Remove Customize Support Class + Inline Script
add_action('admin_bar_menu', function () {
  remove_action('wp_before_admin_bar_render', 'wp_customize_support_script');
}, 50);

// Remove JQuery & JQuery Migrate
add_action('wp_enqueue_scripts', function () {
  // wp_deregister_script('jquery');
  // wp_deregister_script('jquery-migrate');
});

// Remove Classic Theme Styles CSS
add_action('wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 20);
function mywptheme_child_deregister_styles()
{
  wp_dequeue_style('classic-theme-styles');
}

// Remove Duotone support for Gutenberg blocks
add_filter('wp_lazy_loading_enabled', '__return_false');
add_action('init', function () {
  // remove duotone support for Gutenberg blocks
  remove_filter('render_block', 'wp_render_duotone_support');
});

// Disable WP API
add_filter('rest_authentication_errors', function ($result) {
  // If a previous authentication check was applied, pass that result along without modification.
  if (true === $result || is_wp_error($result)) {
    return $result;
  }

  // No authentication has been performed yet.
  // Return an error if user is not logged in.
  if (!is_user_logged_in()) {
    return new WP_Error(
      'rest_not_logged_in',
      __('You are not currently logged in.'),
      array('status' => 401)
    );
  }

  // Our custom authentication check should have no effect on logged-in requests
  return $result;
});
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

// Remove wlwmanifest.xml
remove_action('wp_head', 'wlwmanifest_link');

// Remove xmlrpc
remove_action('wp_head', 'rsd_link');

// Remove WordPress version number
function crunchify_remove_version()
{
  return '';
}
add_filter('the_generator', 'crunchify_remove_version');

// function crunchify_cleanup_query_string($src)
// {
//   // Remove query string from static resources
//   // ?ver=*
//   $parts = explode('?ver=', $src);
//   // &ver=*
//   $parts = explode('&ver=', $parts[0]);
//   return $parts[0];
// }
// add_filter('script_loader_src', 'crunchify_cleanup_query_string', 15, 1);
// add_filter('style_loader_src', 'crunchify_cleanup_query_string', 15, 1);

// Remove Shortlink
remove_action('wp_head', 'wp_shortlink_wp_head');

// Remove HTML comments from source output
function callback($buffer)
{
  // Remove unicode OBJECT REPLACEMENT CHARACTER (U+FFFC)
  $buffer = preg_replace('/\x{FFFC}/u', ' ', $buffer);

  // Custom rewrites
  /* $buffer = preg_replace('/\/wp-content\/uploads\/fbrfg/', home_url(), $buffer);
  $buffer = preg_replace('/\/wp-content\/uploads/', "/uploads", $buffer);
  $buffer = preg_replace('/\/wp-content\/themes\/atelierdesign\/api/', "/api", $buffer);
  $buffer = preg_replace('/\/wp-content\/themes\/atelierdesign\/dist/', "/dist", $buffer);
  $buffer = preg_replace('/\/wp-content\/themes\/atelierdesign\/static/', "/static", $buffer); */
  // $buffer = str_replace(home_url('/uploads/'), 'https://cdn.domain.com/', $buffer);
  // $buffer = str_replace(home_url('/static/'), 'https://cdn.domain.com/static/', $buffer);

  // if url starting with 'https://cdn.domain.com/' ends with .png or .jpg or .jpeg
  // then add .webp to the end of the url --> example .png.webp
  // $buffer = preg_replace('/https:\/\/cdn.domain.com\/(.*?)(.png|.jpg|.jpeg)/', 'https://cdn.domain.com/$1$2.webp', $buffer);

  /* $home_url = get_site_url();
  $home_url_regex = str_replace('/', '\/', $home_url);
  $home_url_regex = str_replace('.', '\.', $home_url_regex);
  $home_url_regex = str_replace('https', 'http(s)?', $home_url_regex);
  $buffer = preg_replace('/' . $home_url_regex . '\//', '/', $buffer); */

  //   $buffer = preg_replace('/http(s):\/\/hellomusic\.m2\.ggweb\.site/', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'], $buffer);

  // $buffer = preg_replace('/<!--(.|\s)*?-->/', '', $buffer);
  $search = array(
    '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
    '/[^\S ]+\</s',     // strip whitespaces before tags, except space
    '/(\s)+/s',         // shorten multiple whitespace sequences
    '/<!--(.|\s)*?-->/' // Remove HTML comments
  );
  $replace = array(
    '>',
    '<',
    '\\1',
    ''
  );
  $buffer = preg_replace($search, $replace, $buffer);

  // Remove PHP_EOL
  $buffer = preg_replace('/\n/', ' ', $buffer);

  return $buffer;
}
function buffer_start()
{
  if (is_user_logged_in()) return;

  if (strpos($_SERVER['REQUEST_URI'], 'robots.txt') !== false) return;

  ob_start("callback");
}
function buffer_end()
{
  if (is_user_logged_in()) return;

  if (strpos($_SERVER['REQUEST_URI'], 'robots.txt') !== false) return;

  ob_end_flush();
}
// add_action('init', 'buffer_start', PHP_INT_MIN);
// add_action('end', 'buffer_end', PHP_INT_MAX);
