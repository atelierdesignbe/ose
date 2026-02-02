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
            0 => 'field-group-hero',
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
