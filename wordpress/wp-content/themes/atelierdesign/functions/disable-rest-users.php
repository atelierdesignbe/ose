<?php

function disable_rest_api_user_list()
{
  if (! is_user_logged_in()) {
    add_filter('rest_endpoints', function ($endpoints) {
      if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
      }
      if (isset($endpoints['/wp/v2/users/(?P<id>\\d+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>\\d+)']);
      }
      return $endpoints;
    });
  }
}
add_action('init', 'disable_rest_api_user_list');
