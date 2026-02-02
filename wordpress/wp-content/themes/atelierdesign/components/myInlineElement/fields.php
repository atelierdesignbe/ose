<?php

/**
 * ACF Fields for a custom element inside sections
 */

// Global variable coming from the AD UI plugin
global $adwp;

// Icon of the block (usually coming from Google's Material Symbols)
// Important to keep 
// - `style="vertical-align: bottom;"` to avoid the icon from being too high
// - `width="16" height="16"` to avoid the icon from being too large
// - `fill="currentColor"` to colorize it the same as other icons in ACF
$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" height="16" viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-7-.5-14.5T799-507q-5 29-27 48t-52 19h-80q-33 0-56.5-23.5T560-520v-40H400v-80q0-33 23.5-56.5T480-720h40q0-23 12.5-40.5T563-789q-20-5-40.5-8t-42.5-3q-134 0-227 93t-93 227h200q66 0 113 47t47 113v40H400v110q20 5 39.5 7.5T480-160Z"/></svg>';

// Fields of the "My Inline Element" element
$myInlineElementFields = [
  [
    'key' => 'field-my-inline-element',
    'label' => 'My Inline Element',
    'name' => 'myInlineElement_myTextField',  // IMPORTANT: The field names must be prefixed with the same name as the layout, and how they what the folder is called in the `components` directory
    'type' => 'text',
  ],
];

// Adding the "My Inline Element" to all the other flexible inline blocks, with the $adwp->add_inline_layout() function
$myInlineElementLayout = [
  'key' => 'layout-myInlineElement',
  'label' => $icon . ' My Inline Element',
  'name' => 'myInlineElement',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('inline-', $myInlineElementFields), // must be unique
];

$adwp->add_inline_layout('layoutMyInlineElement', $myInlineElementLayout, 99);

// We have register the layout again if we want to include it in nested, or deeply nested blocks with the $adwp->add_nested_layout() and $adwp->add_deep_layout() functions
// Nested example: Columns block, Feature section, etc.
// Deeply nested example: Card block, Accordion block, etc.
$myInlineElementNestedLayout = [
  'key' => 'layout-myInlineElement-nested', // must be unique
  'label' => $icon . ' My Inline Element (Nested)',
  'name' => 'myInlineElement',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $myInlineElementFields), // must be unique
];

$adwp->add_nested_layout('layoutMyInlineElementNested', $myInlineElementNestedLayout, 99);

$myInlineElementDeepLayout = [
  'key' => 'layout-myInlineElement-deep', // must be unique
  'label' => $icon . ' My Inline Element (Deeply Nested)',
  'name' => 'myInlineElement',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $myInlineElementFields), // must be unique
];

$adwp->add_deep_layout('layoutMyInlineElementDeep', $myInlineElementDeepLayout, 99);

$adwp->register_layout('myInlineElement', $myInlineElementNestedLayout);
