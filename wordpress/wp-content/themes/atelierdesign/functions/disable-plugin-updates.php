<?php

/**
 * Disable all plugins updates
 */

function ad_disable_plugin_updates($value)
{
  unset($value->response);
  return $value;
}
add_filter('site_transient_update_plugins', 'ad_disable_plugin_updates');
