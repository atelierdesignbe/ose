<?php

/**
 * ACF fields for default page template
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-single-publication-template',
    'title' => 'Custom Flexible Fields',
    'fields' => [
      // // Tab: Hero
      [
        'key' => 'field-single-publication-template-tab-global',
        'label' => 'Hero',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-single-publication-is-external',
        'label' => 'External link ?',
        'name' => 'is-external',
        'type' => 'true_false',
        'default_value' => 0,
        'ui' => 1,
      ],
      [
        'key' => 'field-single-publication-external-link',
        'label' => 'Link',
        'name' => 'external-link',
        'type' => 'url',
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '==',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-template-category',
        'label' => 'Category',
        'name' => 'category',
        'type' => 'true_false',
        'default_value' => 0,
        'ui' => 1,
        'ui_on_text' => 'In depth',
        'ui_off_text' => 'Summary',
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-template-cover',
        'label' => 'Cover',
        'type' => 'image',
        'name' => 'cover',
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-template-description',
        'label' => 'Description',
        'type' => 'textarea',
        'name' => 'description',
        'rows' => 2,
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-template-date',
        'label' => 'Date',
        'type' => 'date_picker',
        'name' => 'date_start',
        'required' => 1,
        'display_format' => 'd-m-Y',      // Format d'affichage dans l'admin
        'return_format' => 'd-m-Y',
        'default_value' => date('d-m-Y'),
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],

      // Tab: Flexible Content
      [
        'key' => 'field-single-publication-template-tab-flexible',
        'label' => 'Flexible Content',
        'type' => 'tab',
        'no_preference' => 0,
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      // Flexible content section
      [
        'key' => 'field-single-publication-template-clone-fieldgroup-flexible',
        'label' => 'Flexible Content',
        'name' => 'flexible_content',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-cta-footer-tab',
        'label' => 'CTA Footer',
        'type' => 'tab',
        'no_preference' => 0,
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-cta-footer-state',
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
        'conditional_logic' => [
          [
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-single-publication-cta-footer-clone',
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
              'field' => 'field-single-publication-cta-footer-state',
              'operator' => '==',
              'value' => 'override',
            ],
            [
              'field' => 'field-single-publication-is-external',
              'operator' => '!=',
              'value' => '1',
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
          'value' => 'publication',
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
      10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);





add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-single-publication-authors',
    'title' => 'Custom Authors',
    'fields' => [
      [
        'key' => 'field-single-publication-template-author',
        'label' => 'Authors',
        'type' => 'relationship',
        'name' => 'author',
        'required' => 0,
        'post_type' => ['author'], 
        'filters' => ['search'],
        // 'elements' => ['featured_image'],  // Afficher la photo
        'min' => 0,
        // 'max' => 5,
        'return_format' => 'object',
        // 'conditional_logic' => [
        //   [
        //     [
        //       'field' => 'field-single-publication-is-external',
        //       'operator' => '!=',
        //       'value' => '1',
        //     ],
        //   ],
        // ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'publication',
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
      10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);
