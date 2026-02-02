<?php

/**
 * ACF Fields for a custom block layout
 */

// Global variable coming from the AD UI plugin
global $adwp;

// Icon of the block (usually coming from Google's Material Symbols)
// Important to keep 
// - `style="vertical-align: bottom;"` to avoid the icon from being too high
// - `width="16" height="16"` to avoid the icon from being too large
// - `fill="currentColor"` to colorize it the same as other icons in ACF
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-7-.5-14.5T799-507q-5 29-27 48t-52 19h-80q-33 0-56.5-23.5T560-520v-40H400v-80q0-33 23.5-56.5T480-720h40q0-23 12.5-40.5T563-789q-20-5-40.5-8t-42.5-3q-134 0-227 93t-93 227h200q66 0 113 47t47 113v40H400v110q20 5 39.5 7.5T480-160Z"/></svg>';

// Fields of the "My Section" block
$mySectionFields = [
  [
    'key' => 'field-my-section',
    'label' => 'My Section',
    'name' => 'mySection_myTextField', // IMPORTANT: The field names must be prefixed with the same name as the layout, and how they what the folder is called in the `components` directory
    'type' => 'text',
  ],
];

// Adding the "My Section" to all the other flexible sections, with the $adwp->add_block_layout() function
$mySectionLayout = [
  'key' => 'layout-mySection',
  'label' => $icon . ' My Section',
  'name' => 'mySection',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('block-', $mySectionFields),
];

$adwp->add_block_layout('layoutMySection', $mySectionLayout, 99);
