<?php

$heroPublicationFields = [

  [
    'key' => 'field-hero-publication-auto',
    'label' => '',
    'name' => 'hero-override',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Override',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-hero-publication-date',
    'label' => 'Date',
    'name' => 'hero-date',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-category',
    'label' => 'Category',
    'name' => 'hero-category',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-project',
    'label' => 'Project',
    'name' => 'hero-project',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-title',
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
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-description',
    'label' => 'Description',
    'name' => 'hero-description',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-type',
    'label' => 'Type',
    'name' => 'hero-type',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-theme',
    'label' => 'Theme',
    'name' => 'hero-theme',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-publication-image-state',
    'label' => 'Cover style',
    'name' => 'cover-status',
    'type' => 'true_false',
    'default_value' => "1",
    'ui' => 1,
    'ui_on_text' => 'Fit',
    'ui_off_text' => 'None',
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
];

$heroPublicationFieldGroup = [
  'key' => 'field-group-hero-publication',
  'title' => 'Hero',
  'fields' => $heroPublicationFields,
];

acf_add_local_field_group($heroPublicationFieldGroup);