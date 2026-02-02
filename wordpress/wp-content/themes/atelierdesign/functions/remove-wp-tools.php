<?php

// Remove Site Health Status from WP Dashboard
function ad_remove_site_health()
{
  remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'ad_remove_site_health');

// Remove Site Health from Tools menu
function ad_remove_site_health_menu()
{
  remove_submenu_page('tools.php', 'site-health.php');
}
add_action('admin_menu', 'ad_remove_site_health_menu');

// Remove Tools from WP Admin Menu
function ad_remove_tools_menu()
{
  remove_menu_page('tools.php');
}
add_action('admin_menu', 'ad_remove_tools_menu');
