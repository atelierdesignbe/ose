<?php

/**
 * ACF fields for default page template
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key' => 'field-group-custom-contact-template',
    'title' => 'Custom Flexible Fields',
    'fields' => [
      // // Tab: Hero
      [
        'key' => 'field-custom-contact-template-tab-hero',
        'label' => 'Hero',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // // Hero section
      [
        'key' => 'field-custom-contact-template-group-hero',
        'label' => 'Hero',
        'name' => 'hero',
        'type' => 'group',
        'sub_fields' => [
          [
            'key' => 'field-custom-contact-template-clone-fieldgroup-hero',
            'label' => 'Hero',
            'name' => 'hero',
            'type' => 'clone',
            'clone' => [
              0 => 'field-group-hero',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-custom-contact-content-tab',
        'label' => 'Content',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      wysiwyg('field-custom-contact-content', ['heading-2xl', 'heading-xl', 'heading-lg'], ['paragraph-xl', 'paragraph-lg',  'paragraph-md']),
      [
        'key' => 'field-form-formidable',
        'label' => 'Select form',
        'name' => 'form_id',
        'type' => 'select',
        'choices' => [], // sera rempli dynamiquement
        'ui' => 1,
        'ajax' => 0,
        // 'placeholder' => '',
      ],
      [
        'key' => 'field-custom-contact-cta-footer-tab',
        'label' => 'CTA Footer',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      [
        'key' => 'field-custom-contact-cta-footer-state',
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
        'key' => 'field-custom-contact-cta-footer-clone',
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
              'field' => 'field-custom-contact-cta-footer-state',
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
          'value' => 'templates/contact.php',
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
