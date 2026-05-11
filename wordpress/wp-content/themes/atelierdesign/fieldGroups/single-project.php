<?php

/**
 * ACF fields for default page template
 */

add_action('acf/include_fields', function () {
  // Choices pour les selects year_start / year_end : année courante + 2 → 1990
  $year_choices = [];
  for ( $y = (int) date('Y') + 15; $y >= 1990; $y-- ) {
    $year_choices[ (string) $y ] = (string) $y;
  }

  acf_add_local_field_group([
    'key' => 'field-group-single-project-template',
    'title' => 'Custom Flexible Fields',
    'fields' => [
      // // Tab: Hero
      [
        'key' => 'field-single-project-template-tab-global',
        'label' => 'Hero',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-hero-project-image-state',
        'label' => 'Cover style',
        'name' => 'cover-status',
        'type' => 'button_group',
        'choices' => [
            'fit'  => 'Fit',
            'fill'  => 'Fill',
            'none'  => 'None',
        ],
        'default_value' => 'fill',
        'layout' => 'horizontal',
        'return_format' => 'value',
      ],
      [
        'key' => 'field-single-project-template-cover',
        'label' => 'Cover',
        'type' => 'image',
        'name' => 'cover',
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
        'conditional_logic' => [
          [
            [
              'field' => 'field-hero-project-image-state',
              'operator' => '!=',
              'value' => 'none',
            ]
          ]
        ]
      ],
      // [
      //   'key' => 'field-single-project-template-description',
      //   'label' => 'Description',
      //   'type' => 'textarea',
      //   'name' => 'description',
      //   'rows' => 2,
      // ],
      [
        'key'           => 'field-single-project-year-start',
        'label'         => 'Start Year',
        'name'          => 'year_start',
        'type'          => 'select',
        'required'      => 1,
        'choices'       => $year_choices,
        'default_value' => (string) date('Y'),
        'allow_null'    => 0,
        'return_format' => 'value',
        'ui'            => 0,
      ],
      [
        'key'           => 'field-single-project-year-end',
        'label'         => 'End Year',
        'name'          => 'year_end',
        'type'          => 'select',
        'required'      => 0,
        'choices'       => $year_choices,
        'default_value' => '',
        'allow_null'    => 1,
        'return_format' => 'value',
        'ui'            => 0,
      ],
      [
        'key'           => 'field-single-project-completed',
        'label'         => '',
        'name'          => 'is_completed',
        'type'          => 'true_false',
        'ui'            => 1,
        'ui_on_text' => 'Completed',
        'ui_off_text' => 'Ongoing',
      ],

      // Tab: Flexible Content
      [
        'key' => 'field-single-project-template-tab-flexible',
        'label' => 'Flexible Content',
        'type' => 'tab',
        'no_preference' => 0,
      ],

      // Flexible content section
      [
        'key' => 'field-single-project-template-clone-fieldgroup-flexible',
        'label' => 'Flexible Content',
        'name' => 'flexible_content',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
      ],
      [
        'key' => 'field-single-project-cta-footer-tab',
        'label' => 'CTA Footer',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-single-project-cta-footer-state',
        'label' => 'CTA status',
        'name' => 'cta_status',
        'type' => 'button_group',
        'choices' => [
            'default'     => 'Default',
            'override'  => 'Override',
            'disabled'  => 'Disabled',
        ],
        'default_value' => 'default',
        'layout' => 'horizontal', // Options : 'horizontal' ou 'vertical'
        'return_format' => 'value',
      ],
      [
        'key' => 'field-single-project-cta-footer-clone',
        'label' => '',
        'name' => 'cta',
        'type' => 'clone',
        'clone' => [
          0 => 'field-group-cta-footer',
        ],
        'display' => 'group',
        'layout' => 'block',
        'required' => 0,
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-project-cta-footer-state',
              'operator' => '==',
              'value' => 'override',
            ],
          ],
        ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'project',
        ],
      ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
    'hide_on_screen' => [
      0 => 'the_content',
      1 => 'excerpt',
      2 => 'discussion',
      3 => 'comments',
      // 4 => 'revisions',
      5 => 'slug',
      6 => 'author',
      // 7 => 'format',
      8 => 'featured_image',
      9 => 'categories',
      // 10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);



add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-single-project-authors',
    'title' => 'Custom Authors',
    'fields' => [
      [
        'key'           => 'field-single-project-template-author',
        'label'         => 'Authors',
        'type'          => 'relationship',
        'name'          => 'author',
        'required'      => 0,
        'post_type'     => ['author', 'external_author'],
        'filters'       => ['search'],
        'min'           => 0,
        'return_format' => 'object',
      ],
      [
        'key'           => 'field-single-project-related-publications',
        'label'         => 'Related Publications',
        'name'          => 'related_publications',
        'type'          => 'relationship',
        'required'      => 0,
        'post_type'     => ['publication'],
        'filters'       => ['search'],
        'min'           => 0,
        'return_format' => 'object',
      ],

    ],
    'location' => [
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'project',
        ],
      ],
    ],
    'menu_order' => 99,
    'position' => 'side',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
    'hide_on_screen' => [
      0 => 'the_content',
      1 => 'excerpt',
      2 => 'discussion',
      3 => 'comments',
      // 4 => 'revisions',
      5 => 'slug',
      6 => 'author',
      // 7 => 'format',
      8 => 'featured_image',
      9 => 'categories',
      // 10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);
