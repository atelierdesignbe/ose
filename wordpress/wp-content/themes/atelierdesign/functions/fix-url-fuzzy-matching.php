<?php

/**
 * Fix URL fuzzy matching
 */

add_filter('redirect_canonical', function ($redirect_url) {
  if (is_404()) {
    return false;
  }
  return $redirect_url;
}, PHP_INT_MAX, 2);
