<?php

/**
 * ACF fields for 'templates/home.php'
 */

global $adwp;

acf_add_local_field_group([
  'key' => 'field-group-template-home',
  'title' => 'Home Template',
  'fields' => [
    // Hero section
    [
      'key' => 'field-home-hero-tab',
      'label' => 'Hero',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-hero',
      'label' => '',
      'name' => 'hero',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-hero',
          'label' => 'Hero',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-home-hero',
          ],
        ],
      ],
    ],
    // AREAS
    [
      'key' => 'field-home-intro-tab',
      'label' => 'Areas',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-intro-title',
      'label' => 'Title',
      'name' => 'intro-title',
      'type' => 'text',
      'required' => 1,
    ],
    [
      'key' => 'field-home-intro-link',
      'label' => 'Link',
      'name' => 'intro-link',
      'type' => 'link',
      'required' => 1,
    ],
    [
      ...wysiwyg('field-home-intro-content', ['heading-xl'], ['paragraph-md', 'paragraph-lg'], 'intro-content'),
      'label' => 'Content',
      'required' => 1
    ],
    [
      'key' => 'field-home-intro-areas',
      'label' => 'Areas',
      'type' => 'repeater',
      'name' => 'intro-areas',
      'sub_fields' => [
        wysiwyg('field-home-intro-area', ['heading-xl'], ['paragraph-md']),
      ]
    ],

    // LAST PUBLICATION
    [
      'key' => 'field-home-publication-tab',
      'label' => 'Last Publications',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-last-publications',
      'label' => '',
      'name' => 'publications',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-publications',
          'label' => 'Publications',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-publications',
          ],
        ],
      ],
    ],

    // GRID
    [
      'key' => 'field-home-grid-tab',
      'label' => 'Grid',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-last-grid',
      'label' => '',
      'name' => 'grid',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-grid',
          'label' => 'Grid',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-grid',
          ],
        ],
      ],
    ],

    // LAST EVENTS 
    [
      'key' => 'field-home-event-tab',
      'label' => 'Last Events',
      'type' => 'tab',
      'no_preference' => 0,
    ],

    [
      'key' => 'field-home-group-last-events',
      'label' => '',
      'name' => 'events',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-events',
          'label' => 'Events',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-events',
          ],
        ],
      ],
    ],

    // LAST PROJECTS
    [
      'key' => 'field-home-project-tab',
      'label' => 'Last Projects',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-group-last-projects',
      'label' => '',
      'name' => 'projects',
      'type' => 'group',
      'sub_fields' => [
        [
          'key' => 'field-home-clone-fieldgroup-projects',
          'label' => 'Projects',
          'name' => 'hero',
          'type' => 'clone',
          'clone' => [
            0 => 'field-group-projects',
          ],
        ],
      ],
    ],

    // INSIGHTS
    [
      'key' => 'field-home-insights-tab',
      'label' => 'Insights',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      ...wysiwyg('field-home-insights-left'),
      'label' => 'Content',
      'required' => 1,
      'name' => 'insights-content'
    ],
    [
      'key' => 'field-home-insights-items',
      'label' => 'Items',
      'name' => 'insights',
      'type' => 'repeater',
      'layout' => 'block',
      'sub_fields' => [
        [
          'key' => 'field-home-insights-items-cover',
          'label' => 'Cover',
          'type' => 'image',
          'name' => 'cover',
          'preview_size' => 'thumbnail',
          'library' => 'all',
          'mime_types' => 'jpg,jpeg,png,svg,webp',
          'required' => 1,
        ],
        [
          'key' => 'field-home-insights-items-content',
          'label' => 'Content',
          'type' => 'textarea',
          'name' => 'content',
          'rows' => 2,
        ],
        [
          'key' => 'field-home-insights-items-date',
          'label' => 'Date',
          'type' => 'date_picker',
          'name' => 'date',
          'display_format' => 'd-m-Y',      // Format d'affichage dans l'admin
          'return_format' => 'd-m-Y',

        ],
        [
          'key' => 'field-home-insights-items-link',
          'name' => 'link',
          'label' => 'Link',
          'type' => 'link',
          'required' => 1
        ]
      ]
    ],
    // CTA
    [
      'key' => 'field-home-cta-footer-tab',
      'label' => 'CTA Footer',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-cta-footer-state',
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
      'key' => 'field-home-cta-footer-clone',
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
            'field' => 'field-home-cta-footer-state',
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
        'param' => 'page_template',
        'operator' => '==',
        'value' => 'templates/home.php',
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
