<?php

$eventsFields = [
  wysiwyg('field-related-events-content', ['heading-2xl', 'heading-xl', 'heading-lg'], ['paragraph-lg', 'paragraph-md']),
  [
    'key' => 'field-related-events-link',
    'name' => 'link',
    'label' => 'Link',
    'type' => 'link',
  ],
  [
    'key' => 'field-related-events-auto',
    'label' => '',
    'name' => 'isCustom',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Custom',
    'ui_off_text' => 'Auto',
  ],

  [
    'key' => 'field-related-events-custom',
    'label' => 'Pick events',
    'name' => 'items',
    'type' => 'relationship',
    'post_type' => ['event'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
    'max' =>  2,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-events-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ]
];

$eventsFieldGroup = [
  'key' => 'field-group-events',
  'title' => 'Events',
  'fields' => $eventsFields,
];

acf_add_local_field_group($eventsFieldGroup);