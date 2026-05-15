<?php

/**
 * Remove capabilities from Editor role
 */

function ad_change_editor_capabilities()
{
  $editor = get_role('editor');

  if (!$editor instanceof WP_Role) {
    return;
  }

  $editor->add_cap('list_users');
  $editor->add_cap('edit_users');
  $editor->add_cap('create_users');
  $editor->add_cap('delete_users');
  $editor->add_cap('promote_users');
  $editor->add_cap('publish_posts');
  $editor->add_cap('delete_posts');
  $editor->add_cap('delete_others_posts');
  $editor->add_cap('create_pages');
  $editor->add_cap('publish_pages');
  $editor->add_cap('delete_pages');
  $editor->add_cap('delete_others_pages');

  $editor->remove_cap('edit_theme_options');
}
add_action('init', 'ad_change_editor_capabilities');

function ad_is_non_operator_user_manager()
{
  return current_user_can('list_users') && !current_user_can('administrator') && !ad_current_user_has_role('user');
}

function ad_filter_editable_roles($roles)
{
  if (ad_is_non_operator_user_manager() && isset($roles['administrator'])) {
    unset($roles['administrator']);
  }

  return $roles;
}
add_filter('editable_roles', 'ad_filter_editable_roles');

function ad_current_user_has_role($role)
{
  $user = wp_get_current_user();

  if (!$user instanceof WP_User) {
    return false;
  }

  return in_array($role, (array) $user->roles, true);
}

function ad_remove_users_menu_for_user_role()
{
  if (ad_current_user_has_role('user')) {
    remove_menu_page('users.php');
  }
}
add_action('admin_menu', 'ad_remove_users_menu_for_user_role', 999);

// tie together the publish_pages and create_pages
function ad_joint_publish_pages_cap()
{
  global $wp_post_types;
  if (!current_user_can('publish_pages')) {
    // unset($wp_post_types['page']->cap->create_posts);
    // unset($wp_post_types['page']->cap->edit_attributes);
  }
}
add_action('init', 'ad_joint_publish_pages_cap');

// remove page attributes meta box
function ad_remove_page_attributes_meta_box()
{
  if (!current_user_can('publish_pages')) {
    remove_meta_box('pageparentdiv', 'page', 'side');
  }
}
add_action('admin_menu', 'ad_remove_page_attributes_meta_box');

/**
 * Filtering the user Delete & Edit link, making it only visible for 'editor' users when the target user is not an 'administrator'
 */
function ad_filter_user_row_actions($actions, $user_object)
{
  if (ad_is_non_operator_user_manager()) {
    // get role of user being edited
    $user = new WP_User($user_object->ID);
    if (in_array('administrator', $user->roles)) {
      unset($actions['delete']);
      unset($actions['edit']);
    }
  }
  return $actions;
}
add_filter('user_row_actions', 'ad_filter_user_row_actions', 10, 2);

/**
 * Hide 'administrator' users alltogether from the list of users for 'editor' users
 */
function ad_exclude_admin_users($query)
{
  if (ad_is_non_operator_user_manager()) {
    global $wpdb;
    // Remove 'administrator' from the query of listing users
    $query->query_where = str_replace(
      'WHERE 1=1',
      "WHERE 1=1 AND {$wpdb->users}.ID NOT IN (
        SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
        WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
        AND {$wpdb->usermeta}.meta_value LIKE '%administrator%'
      )",
      $query->query_where
    );
  }
}
add_action('pre_user_query', 'ad_exclude_admin_users');

// Hide .subsubsub on users.php
function ad_hide_subsubsub()
{
  if (ad_is_non_operator_user_manager()) {
    if (str_contains($_SERVER['REQUEST_URI'], 'users.php')) {
      echo '<style>.subsubsub{display:none;}</style>';
    }
  }
}
add_action('admin_head', 'ad_hide_subsubsub');
