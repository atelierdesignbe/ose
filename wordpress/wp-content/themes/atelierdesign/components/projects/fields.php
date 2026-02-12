<?php

$projectsFields = [
  wysiwyg('field-related-project-content', ['heading-2xl', 'heading-xl', 'heading-lg'], ['paragraph-lg', 'paragraph-md']),
  [
    'key' => 'field-related-project-link',
    'name' => 'link',
    'label' => 'Link',
    'type' => 'link',
  ],
  [
    'key' => 'field-related-project-cover',
    'name' => 'cover',
    'label' => 'Cover',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'mime_types' => 'jpg,jpeg,png,svg,webp',
    // 'required' => 1,
  ],
  [
    'key' => 'field-related-project-auto',
    'label' => '',
    'name' => 'isCustom',
    'type' => 'true_false',
    'default_value' => 0,
    'ui' => 1,
    'ui_on_text' => 'Custom',
    'ui_off_text' => 'Auto',
  ],
  [
    'key' => 'field-related-project-themes',
    'label' => 'Themes',
    'instructions' => 'Limit the projects with the selected themes',
    'name' => 'themes',
    'type' => 'taxonomy',
    'field_type' => 'select',
    'taxonomy' => 'themes',
    'allow_null' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-project-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-related-project-types',
    'label' => 'Types',
    'name' => 'types',
    'type' => 'taxonomy',
    'instructions' => 'Limit the projects with the selected types',
    'field_type' => 'select', // 'checkbox', 'multi_select', 'radio'
    'taxonomy' => 'types', // Le slug de ta taxonomie
    'allow_null' => 1,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-project-auto',
          'operator' => '!=',
          'value' => '1',
        ]
      ]
    ]
  ],
  [
    'key' => 'field-related-project-custom',
    'label' => 'Pick project',
    'name' => 'items',
    'type' => 'relationship',
    'post_type' => ['project'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
    'max' =>  2,
    'conditional_logic' => [
      [
        [
          'field' => 'field-related-project-auto',
          'operator' => '==',
          'value' => '1',
        ]
      ]
    ]
  ]
];

$projectsFieldGroup = [
  'key' => 'field-group-projects',
  'title' => 'Projects',
  'fields' => $projectsFields,
];

acf_add_local_field_group($projectsFieldGroup);