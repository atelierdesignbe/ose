<?php

$publicationsFields = [
  wysiwyg('field-related-publication-content', ['heading-2xl', 'heading-xl', 'heading-lg'], ['paragraph-lg', 'paragraph-md']),
  [
    'key' => 'field-related-publication-link',
    'name' => 'link',
    'label' => 'Link',
    'type' => 'link',
  ],
  [
    'key' => 'field-related-publication-auto',
    'label' => '',
    'name' => 'isCustom',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Custom',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-related-publication-themes',
    'label' => 'Themes',
    'instructions' => 'Limit the publications with the selected themes',
    'name' => 'themes',
    'type' => 'taxonomy',
    'field_type' => 'select',
    'taxonomy' => 'themes',
    'allow_null' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-publication-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-related-publication-types',
    'label' => 'Types',
    'name' => 'types',
    'type' => 'taxonomy',
    'instructions' => 'Limit the publications with the selected types',
    'field_type' => 'select', // 'checkbox', 'multi_select', 'radio'
    'taxonomy' => 'types', // Le slug de ta taxonomie
    'allow_null' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-publication-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-related-publication-custom',
    'label' => 'Pick publication',
    'name' => 'items',
    'type' => 'relationship',
    'post_type' => ['publication'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
    'max' =>  2,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-publication-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ]
];

$publicationsFieldGroup = [
  'key' => 'field-group-publications',
  'title' => 'Publications',
  'fields' => $publicationsFields,
];

acf_add_local_field_group($publicationsFieldGroup);