<?php

$heroCPTFields = [
  [
    'key' => 'field-hero-cpt-label-state',
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
    'key' => 'field-hero-cpt-label',
    'label' => 'Label',
    'name' => 'label',
    'type' => 'text',
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-cpt-label-state',
          'operator' => '==',
          'value' => 'override',
        ]
      ]
    ]
  ],

  [
    'key' => 'field-hero-cpt-date',
    'label' => 'Date',
    'name' => 'hero-date',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
  ],
  [
    'key' => 'field-hero-cpt-title',
    'label' => 'Edit Title',
    'instructions' => 'Only for the hero section',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    // 'required' => 1,
    'new_lines' => 'br',
  ],
  [
    'key' => 'field-hero-cpt-content',
    'label' => 'Content',
    'name' => 'content',
    'type' => 'textarea',
    'rows' => 1,
    // 'required' => 1,
    'new_lines' => 'br',
  ],
  [
    'key' => 'field-hero-cpt-image-state',
    'label' => 'Cover style',
    'name' => 'cover-status',
    'type' => 'button_group',
    'choices' => [
        'default'     => 'Fullsize',
        'fit'  => 'Fit',
        'fill'  => 'Fill',
        'none'  => 'None',
    ],
    'default_value' => 'default',
    'layout' => 'horizontal',
    'return_format' => 'value',
  ],
  [
    'key' => 'field-hero-cpt-media-image',
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
          'field' => 'field-hero-cpt-image-state',
          'operator' => '!=',
          'value' => 'none',
        ]
      ]
    ]
  ],
];

$heroCPTFieldGroup = [
  'key' => 'field-group-hero-cpt',
  'title' => 'Hero',
  'fields' => $heroCPTFields,
];

acf_add_local_field_group($heroCPTFieldGroup);