<?php


global $adwp, $adui_tokens, $partials;

// Collect color system mode names
$colorSystemModes = array_keys($adui_tokens['colorSystem'] ?? []);
$layoutNames = array_keys($adui_tokens['colorSystem'][$colorSystemModes[0]]['layout'] ?? []);

// Build a palette mapping hex => "{mode}/main" and a sensible default
$colors = [];
foreach ($colorSystemModes as $mode) {
  foreach ($layoutNames as $layout) {
    $color = getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layout, $mode);
    $colors[$color] = $mode . '/' . $layout;
  }
}

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
      // [
      //   'key' => 'field-cta-footer-color',
      //   'label' => '',
      //   'name' => 'color',
      //   'type' => 'color_picker',
      //   'required' => 1,
      //   'default_value' => getResolvedValue($adui_tokens['colorSystem'], 'layout.' . $layoutNames[0], $colorSystemModes[0]),
      //   'enable_opacity' => 0,
      //   'return_format' => 'label',
      //   'display' => 'palette',
      //   'color_picker' => 0,
      //   'allow_null' => 0,
      //   'theme_colors' => 0,
      //   'colors' => $colors,
      //   'button_label' => 'Select Color',
      //   'absolute' => false,
      //   'input' => false,
      // ],
      // $partials->wysiwyg('field-cta-footer-content', ['heading-xl'], ['paragraph-md']),
      [
        'key' => 'field-cta-footer-link',
        'label' => 'Link',
        'name' => 'link',
        'type' => 'link',
        'required' => 1,
      ]
    ]
  ],
  // [
  //   'key' => 'field-cta-footer-global-link',
  //   'label' => 'Link',
  //   'name' => 'link',
  //   'type' => 'link',
  // ]
];

$ctafieldsGroup = [
  'key' => 'field-group-cta-footer',
  'title' => 'CTA footer',
  'fields' => $ctafields,
];

acf_add_local_field_group($ctafieldsGroup);