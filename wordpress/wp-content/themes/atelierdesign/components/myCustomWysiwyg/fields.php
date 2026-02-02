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

// Fields of the "My Custom Wysiwyg" element
$myCustomWysiwygFields = [
  [
    'key' => 'field-my-custom-wysiwyg',
    'label' => '',
    'name' => 'myCustomWysiwyg_myWysiwyg',  // IMPORTANT: The field names must be prefixed with the same name as the layout, and how they what the folder is called in the `components` directory
    'type' => 'wysiwyg',
    'media_upload' => 0,
    'tabs' => 'visual',
    'acfe_wysiwyg_min_height' => 70,
    'acfe_wysiwyg_max_height' => '',
    'acfe_wysiwyg_valid_elements' => 'h1,h1[class],h2,h2[class],h3,h3[class],h4,h4[class],h5,h5[class],h6,h6[class],p,p[class],ul,ul[class],ol,ol[class],li,li[class],a,a[class],br,em,em[class],i,i[class],b,b[class],strong,strong[class],span,span[class]',
    // IMPORTANT: only works when the WP instance is not in a subfolder (so no dev.atelierdesign.be/SOMETHING/; should work on production domains, or on localhost)
    'acfe_wysiwyg_custom_style' => 'src/components/myCustomWysiwyg/editor.css',
    'acfe_wysiwyg_disable_wp_style' => 0,
    'acfe_wysiwyg_autoresize' => 1,
    'acfe_wysiwyg_disable_resize' => 1,
    'acfe_wysiwyg_remove_path' => 1,
    'acfe_wysiwyg_menubar' => 0,
    'acfe_wysiwyg_transparent' => 0,
    'acfe_wysiwyg_merge_toolbar' => 0,
    'acfe_wysiwyg_custom_toolbar' => 1,
    'acfe_wysiwyg_toolbar_buttons' => [
      'acfe_wysiwyg_toolbar_1' => [
        [
          'acfe_wysiwyg_toolbar_row' => 'typography-selector',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'bold',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'italic',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'heading-highlight',
        ],
        // [
        //   'acfe_wysiwyg_toolbar_row' => 'alignleft',
        // ],
        // [
        //   'acfe_wysiwyg_toolbar_row' => 'aligncenter',
        // ],
        [
          'acfe_wysiwyg_toolbar_row' => 'balance-text',
        ],
        // [
        //   'acfe_wysiwyg_toolbar_row' => 'bullist',
        // ],
        // [
        //   'acfe_wysiwyg_toolbar_row' => 'numlist',
        // ],
        [
          'acfe_wysiwyg_toolbar_row' => 'link',
        ],
      ],
    ],
    'acfe_wysiwyg_auto_init' => 0,
    'acfe_wysiwyg_height' => 70,
    'ad_typography_config' => [
      ...ad_get_default_typography_config([
        'heading-2xl',
        'heading-xl',
        'heading-lg',
        'heading-md',
        'heading-sm',
        'paragraph-xl',
        'paragraph-lg',
        'paragraph-md',
        'paragraph-sm',
        'label',
      ]),
      [
        'key' => 'text-red',
        'text' => 'Red',
        'format' => ['block' => 'p', 'classes' => 'text-red'],
      ],
    ],
  ],
];

// Adding the "My Custom Wysiwyg" to all the other flexible inline blocks, with the $adwp->add_inline_layout() function
$myCustomWysiwygLayout = [
  'key' => 'layout-myCustomWysiwyg',
  'label' => $icon . ' My Custom Wysiwyg',
  'name' => 'myCustomWysiwyg',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('inline-', $myCustomWysiwygFields), // must be unique
];

$adwp->add_inline_layout('layoutmyCustomWysiwyg', $myCustomWysiwygLayout, 99);

// We have register the layout again if we want to include it in nested, or deeply nested blocks with the $adwp->add_nested_layout() and $adwp->add_deep_layout() functions
// Nested example: Columns block, Feature section, etc.
// Deeply nested example: Card block, Accordion block, etc.
$myCustomWysiwygNestedLayout = [
  'key' => 'layout-myCustomWysiwyg-nested', // must be unique
  'label' => $icon . ' My Custom Wysiwyg (Nested)',
  'name' => 'myCustomWysiwyg',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('nested-', $myCustomWysiwygFields), // must be unique
];

$adwp->add_nested_layout('layoutmyCustomWysiwygNested', $myCustomWysiwygNestedLayout, 99);

$myCustomWysiwygDeepLayout = [
  'key' => 'layout-myCustomWysiwyg-deep', // must be unique
  'label' => $icon . ' My Custom Wysiwyg (Deeply Nested)',
  'name' => 'myCustomWysiwyg',
  'display' => 'block',
  'sub_fields' => prefix_fields_keys('deep-', $myCustomWysiwygFields), // must be unique
];

$adwp->add_deep_layout('layoutmyCustomWysiwygDeep', $myCustomWysiwygDeepLayout, 99);

$adwp->register_layout('myCustomWysiwyg', $myCustomWysiwygNestedLayout);
