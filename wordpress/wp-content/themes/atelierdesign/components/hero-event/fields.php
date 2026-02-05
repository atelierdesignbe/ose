<?php

$heroEventFields = [

  [
    'key' => 'field-hero-event-auto',
    'label' => '',
    'name' => 'hero-override',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Override',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-hero-event-date',
    'label' => 'Date',
    'name' => 'hero-date',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-event-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-event-title',
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
          'field' => 'field-hero-event-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-event-description',
    'label' => 'Description',
    'name' => 'hero-description',
    'type' => 'true_false',
    'default_value' => 1,
    'ui' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-hero-event-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-hero-event-image-state',
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
          'field' => 'field-hero-event-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],
];

$heroEventFieldGroup = [
  'key' => 'field-group-hero-event',
  'title' => 'Hero',
  'fields' => $heroEventFields,
];

acf_add_local_field_group($heroEventFieldGroup);