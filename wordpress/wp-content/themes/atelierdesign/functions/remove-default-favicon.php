<?php
// Remove default WordPress favicon

function remove_default_favicon()
{
  remove_action('wp_head', 'wp_site_icon', PHP_INT_MAX);
}
add_action('wp_head', 'remove_default_favicon', PHP_INT_MAX);

// Remove it from the admin area as well
function remove_default_favicon_admin()
{
  remove_action('admin_head', 'wp_site_icon', PHP_INT_MAX);
}
add_action('admin_head', 'remove_default_favicon_admin', PHP_INT_MAX);
