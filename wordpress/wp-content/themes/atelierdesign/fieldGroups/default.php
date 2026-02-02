<?php

/**
 * ACF fields for default page template
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-custom-default-template',
    'title' => 'Custom Flexible Fields',
    'fields' => [
      // // Tab: Hero
      // [
      //   'key' => 'field-custom-default-template-tab-hero',
      //   'label' => 'Hero',
      //   'type' => 'tab',
      //   'no_preference' => 0,
      // ],
      // // Hero section
      // [
      //   'key' => 'field-custom-default-template-group-hero',
      //   'label' => 'Hero',
      //   'name' => 'hero',
      //   'type' => 'group',
      //   'sub_fields' => [
      //     [
      //       'key' => 'field-custom-default-template-clone-fieldgroup-hero',
      //       'label' => 'Hero',
      //       'name' => 'hero',
      //       'type' => 'clone',
      //       'clone' => [
      //         0 => 'field-group-hero',
      //       ],
      //     ],
      //   ],
      // ],
      // Tab: Flexible Content
      [
        'key' => 'field-custom-default-template-tab-flexible',
        'label' => 'Flexible Content',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // Flexible content section
      [
        'key' => 'field-custom-default-template-clone-fieldgroup-flexible',
        'label' => 'Flexible Content',
        'name' => 'flexible_content',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'default',
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
