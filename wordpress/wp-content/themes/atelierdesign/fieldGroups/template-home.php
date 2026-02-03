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
    [
      'key' => 'field-home-cta-footer-tab',
      'label' => 'CTA Footer',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-home-cta-footer-override',
      'label' => '',
      // 'instructions' => 'Enabled it if you want to override CTA footer from "General Options"',
      'name' => 'cta-override',
      'type' => 'true_false',
      'ui' => 1,
      'ui_on_text' => 'Override',
      'ui_off_text' => 'Auto',
      'default_value' => 0,
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
            'field' => 'field-home-cta-footer-override',
            'operator' => '==',
            'value' => 1,
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
