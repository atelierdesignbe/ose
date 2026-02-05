<?php

$heroProjectFields = [

  [
    'key' => 'field-hero-project-auto',
    'label' => '',
    'name' => 'hero-override',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Override',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-hero-project-date',
    'label' => 'Date',
    'name' => 'hero-date',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-project-title',
    'label' => 'Edit Title',
    'instructions' => 'Only for the hero section',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    // 'required' => 1,
    'new_lines' => 'br',
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-project-description',
    'label' => 'Description',
    'name' => 'hero-description',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-project-type',
    'label' => 'Type',
    'name' => 'hero-type',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-project-theme',
    'label' => 'Theme',
    'name' => 'hero-theme',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-project-image-state',
    'label' => 'Cover style',
    'name' => 'cover-status',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'ui_on_text' => 'Fit',
    'ui_off_text' => 'None',
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
];

$heroProjectFieldGroup = [
  'key' => 'field-group-hero-project',
  'title' => 'Hero',
  'fields' => $heroProjectFields,
];

acf_add_local_field_group($heroProjectFieldGroup);