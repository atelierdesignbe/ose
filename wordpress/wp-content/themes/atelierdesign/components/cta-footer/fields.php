<?php


// global $adwp, $adui_tokens, $partials;

// Collect color system mode names
// $colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
// $layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

// // Build a palette mapping hex => "{mode}/main" and a sensible default
// $colors = [];
// foreach ($colorSystemModes as $mode) {
//   foreach ($layoutNames as $layout) {
//     $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
//     $colors[$color] = $mode . '/' . $layout;
//   }
// }

$ctafields = [
  [
    'key' => 'field-cta-footer-items',
    'label' => '',
    'name' => 'items',
    'type' => 'repeater',
    'layout' => 'block',
    'button_label' => 'Add CTA',
    'min' => 1,
    'max' => 2,
    'sub_fields' => [
      wysiwyg('field-cta-footer-content', ['heading-xl'], ['paragraph-md']),
      [
        'key' => 'field-cta-footer-link',
        'label' => 'Link',
        'name' => 'link',
        'type' => 'link',
        'required' => 1,
      ]
    ]
  ],
];

$ctafieldsGroup = [
  'key' => 'field-group-cta-footer',
  'title' => 'CTA footer',
  'fields' => $ctafields,
];

acf_add_local_field_group($ctafieldsGroup);