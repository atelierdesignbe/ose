<?php

$headerFields = [
  [
    'key' => 'field-header-nav',
    'label' => 'Navigation',
    'name' => 'header_nav',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-header-nav-items',
        'label' => 'Items',
        'name' => 'items',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add link',
        'sub_fields' => [
          [
            'key' => 'field-header-nav-item-link',
            'label' => 'Select a link',
            'name' => 'link',
            'type' => 'link',
            'required' => 1,
          ],
        ],
      ],
    ],
  ],
  [
    'key' => 'field-header-cta',
    'label' => 'CTA Button (optional)',
    'name' => 'header_cta',
    'type' => 'group',
    'sub_fields' => [
      [
        'key' => 'field-header-cta-link',
        'label' => '',
        'name' => 'link',
        'type' => 'link',
      ],
    ],
  ],
];

$headerFieldGroup = [
  'key' => 'field-group-header',
  'title' => 'header',
  'fields' => $headerFields,
];

acf_add_local_field_group($headerFieldGroup);
