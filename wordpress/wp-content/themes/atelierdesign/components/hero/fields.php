<?php

$heroFields = [
  [
    'key' => 'field-hero-label-state',
    'label' => 'Label status',
    'name' => 'label-status',
    'type' => 'button_group',
    'choices' => [
        'default'     => 'Default',
        'override'  => 'Override',
        'disabled'  => 'Disabled',
    ],
    'default_value' => 'default',
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-hero-label',
    'label' => 'Label',
    'name' => 'label',
    'type' => 'text',
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-label-state',
          'operator' => '==',
          'value' => 'override',
        ]
      ]
    ]
  ],

  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    'required' => 1,
    'new_lines' => 'br'
  ],
  [
    'key' => 'field-hero-content',
    'label' => 'Content',
    'name' => 'content',
    'type' => 'textarea',
    'rows' => 2,
  ],
  [
    'key' => 'field-hero-image-state',
    'label' => 'Cover style',
    'name' => 'cover-status',
    'type' => 'button_group',
    'choices' => [
        'default'     => 'Fullsize',
        'fit'  => 'Fit',
        'fill'  => 'Fill',
        'none'  => 'None',
    ],
    'default_value' => 'fullsize',
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-hero-media-image',
    'label' => 'Cover',
    'name' => 'cover',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'mime_types' => 'jpg,jpeg,png,svg,webp',
    'required' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-image-state',
          'operator' => '!=',
          'value' => 'none',
        ]
      ]
    ]
  ],
];

$heroFieldGroup = [
  'key' => 'field-group-hero',
  'title' => 'Hero',
  'fields' => $heroFields,
];

acf_add_local_field_group($heroFieldGroup);