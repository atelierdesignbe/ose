<?php
// Remove Appearance
function ad_remove_themes_menu()
{
  if (get_template() == 'atelierdesign') {
    remove_menu_page('themes.php');
  }
}
add_action('admin_menu', 'ad_remove_themes_menu');
