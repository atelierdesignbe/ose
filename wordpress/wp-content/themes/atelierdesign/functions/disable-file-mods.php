<?php
/*
* Disables the plugin and theme file editor in the admin and plugin and theme updates and installation from the admin
*/

if (is_blog_installed()) {

  // Disable the plugin and theme file editor in the admin
  define('DISALLOW_FILE_EDIT', true);

  // Disable plugin and theme updates and installation from the admin
  define('DISALLOW_FILE_MODS', true);
}
