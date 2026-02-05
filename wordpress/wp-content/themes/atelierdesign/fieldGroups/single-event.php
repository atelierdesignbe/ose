<?php

/**
 * ACF fields for default page template
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-single-event-template',
    'title' => 'Custom Flexible Fields',
    'fields' => [
      // // Tab: Hero
      [
        'key' => 'field-single-event-template-tab-global',
        'label' => 'Global',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-single-event-template-cover',
        'label' => 'Cover',
        'type' => 'image',
        'name' => 'cover',
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
      ],
      [
        'key' => 'field-single-event-template-description',
        'label' => 'Description',
        'type' => 'textarea',
        'name' => 'description',
        'rows' => 2,
      ],
      [
        'key' => 'field-single-event-template-date-start',
        'label' => 'Start date',
        'type' => 'date_picker',
        'name' => 'date_start',
        'required' => 1,
        'display_format' => 'd-m-Y',      // Format d'affichage dans l'admin
        'return_format' => 'd-m-Y',
      ],
      [
        'key' => 'field-single-event-template-date-end',
        'label' => 'End date',
        'type' => 'date_picker',
        'name' => 'date_end',
        'display_format' => 'd-m-Y',      // Format d'affichage dans l'admin
        'return_format' => 'd-m-Y',
        // 'required' => 1,
      ],
      [
        'key' => 'field-single-event-template-tab-hero',
        'label' => 'Hero',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // // Hero section
      [
        'key' => 'field-single-event-template-group-hero',
        'label' => 'Hero',
        'name' => 'hero',
        'type' => 'group',
        'sub_fields' => [
          [
            'key' => 'field-single-event-template-clone-fieldgroup-hero',
            'label' => 'Hero',
            'name' => 'hero',
            'type' => 'clone',
            'clone' => [
              0 => 'field-group-hero-event',
            ],
          ],
        ],
      ],
      // Tab: Flexible Content
      [
        'key' => 'field-single-event-template-tab-flexible',
        'label' => 'Flexible Content',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // Flexible content section
      [
        'key' => 'field-single-event-template-clone-fieldgroup-flexible',
        'label' => 'Flexible Content',
        'name' => 'flexible_content',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
      ],
      [
        'key' => 'field-single-event-cta-footer-tab',
        'label' => 'CTA Footer',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-single-event-cta-footer-state',
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
        'key' => 'field-single-event-cta-footer-clone',
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
              'field' => 'field-single-event-cta-footer-state',
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
          'value' => 'event',
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
