<?php
global $adwp;

$icon = '<svg style="vertical-align: bottom;" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 512 512" width="16"><path d="M384 336a63.78 63.78 0 0 0-46.12 19.7l-148-83.27a63.85 63.85 0 0 0 0-32.86l148-83.27a63.8 63.8 0 1 0-15.73-27.87l-148 83.27a64 64 0 1 0 0 88.6l148 83.27A64 64 0 1 0 384 336z"></path></svg>';

$relatedProjectsFields = [
  wysiwyg('field-group-related-project-content', ['heading-2xl', 'heading-xl', 'heading-lg'], ['paragraph-lg', 'paragraph-md']),
  [
    'key' => 'field-group-related-project-auto',
    'label' => '',
    'name' => 'isCustom',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Custom',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-group-related-project-themes',
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
          'field' => 'field-group-related-project-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-group-related-project-types',
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
          'field' => 'field-group-related-project-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],

  [
    'key' => 'field-group-related-project-custom',
    'label' => 'Pick publication',
    'name' => 'items',
    'type' => 'relationship',
    'post_type' => ['publication'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
    'max' =>  4,
    'conditional_logic' => [
      [
        [
          'field' => 'field-group-related-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ],    
];

$relatedProjectLayout = [
  'key' => 'layout-related-projects',
  'label' => $icon . ' Related projects',
  'name' => 'related-projects',
  'display' => 'block',
  'sub_fields' => $relatedProjectsFields,
  'acfe_flexible_layouts_settings' => 1,
  'acfe_flexible_settings' => [
    0 => 'field-group-section-settings',
  ],
  'acfe_flexible_settings_size' => 'medium',
];

$adwp->add_block_layout('layoutrelatedProject', $relatedProjectLayout, 99);