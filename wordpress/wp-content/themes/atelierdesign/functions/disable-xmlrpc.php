<?php

/**
 * Disable WordPress XML-RPC API
 * 
 * The XML-RPC API can be a security vulnerability as it provides
 * an additional attack surface for DDoS and brute force attacks.
 * This file completely disables XML-RPC functionality.
 */

// Disable XML-RPC completely
add_filter('xmlrpc_enabled', '__return_false');

// Remove XML-RPC pingback functionality
add_filter('xmlrpc_methods', function ($methods) {
  unset($methods['pingback.ping']);
  unset($methods['pingback.extensions.getPingbacks']);
  return $methods;
});

// Remove XML-RPC RSD link from head
remove_action('wp_head', 'rsd_link');

// Remove Windows Live Writer manifest link
remove_action('wp_head', 'wlwmanifest_link');

// Disable XML-RPC authentication
add_filter('xmlrpc_login_error', function ($error, $user) {
  return new WP_Error('xmlrpc_disabled', __('XML-RPC services are disabled on this site.'));
}, 10, 2);

// Block XML-RPC requests at the server level
add_action('init', function () {
  if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    die('XML-RPC services are disabled on this site.');
  }
});

// Remove X-Pingback header
add_filter('wp_headers', function ($headers) {
  unset($headers['X-Pingback']);
  return $headers;
});

// Block access to xmlrpc.php file
add_action('init', function () {
  if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/xmlrpc.php') {
    http_response_code(403);
    header('Content-Type: text/plain; charset=UTF-8');
    die('XML-RPC services are disabled on this site.');
  }
});
