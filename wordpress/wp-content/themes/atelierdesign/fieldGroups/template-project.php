<?php

/**
 * ACF fields for 'templates/home.php'
 */

global $adwp;

acf_add_local_field_group([
  'key' => 'field-group-template-project',
  'title' => 'Project Template',
  'fields' => [
    [
      'key' => 'field-project-hero',
      'label' => 'Hero',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-project-content',
      'label' => 'Content',
      'name' => 'content',
      'type' => 'textarea',
      'rows' => 2
    ],
    // CTA
    [
      'key' => 'field-project-cta-footer-tab',
      'label' => 'CTA Footer',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-project-cta-footer-state',
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
      'key' => 'field-project-cta-footer-clone',
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
            'field' => 'field-project-cta-footer-state',
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
        'value' => 'templates/projects.php',
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
