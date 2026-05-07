<?php

/**
 * ACF fields for single member (CPT: member)
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key'    => 'field-group-single-member',
    'title'  => 'Member Fields',
    'fields' => [

      // Tab: Info
      // [
      //   'key'   => 'field-single-member-tab-info',
      //   'label' => 'Info',
      //   'type'  => 'tab',
      //   'no_preference' => 0,
      // ],

      [
        'key' => 'field-single-member-cover',
        'label' => 'Cover',
        'type' => 'image',
        'name' => 'cover',
        'preview_size' => 'thumbnail',
        'library' => 'all',
        'mime_types' => 'jpg,jpeg,png,svg,webp',
      ],
      
      [
        'key'   => 'field-single-member-role',
        'label' => 'Role / Position',
        'name'  => 'role',
        'type'  => 'text',
      ],
      [
        ...wysiwyg('field-single-member-summary', ['heading-xl'], ['paragraph-md', 'paragraph-lg', 'paragraph-xl'], 'submary'),
        'label'        => 'Summary',
        'instructions'        => 'Small description about member',
      ],

      // [
      //   'key'          => 'field-single-member-summary',
    
      //   'name'         => 'submary',
      //   'type'         => 'textarea',
      // ],
      // [
      //   'key'          => 'field-single-member-email',
      //   'label'        => 'Email',
      //   'name'         => 'email',
      //   'type'         => 'email',
      // ],
      
      // [
      //   'key' => 'field-single-project-template-tab-flexible',
      //   'label' => 'Flexible Content',
      //   'type' => 'tab',
      //   'no_preference' => 0,
      // ],
      // // Flexible content section
      // [
      //   'key' => 'field-single-project-template-clone-fieldgroup-flexible',
      //   'label' => 'Flexible Content',
      //   'name' => 'flexible_content',
      //   'type' => 'clone',
      //   'clone' => [
      //     0 => 'field-flexible-flexible-layout',
      //   ],
      // ],
      // [
      //   'key'   => 'field-single-member-linkedin',
      //   'label' => 'LinkedIn',
      //   'name'  => 'linkedin',
      //   'type'  => 'url',
      // ],
      // [
      //   'key'   => 'field-single-member-twitter',
      //   'label' => 'Twitter / X',
      //   'name'  => 'twitter',
      //   'type'  => 'url',
      // ],
    
    ],
    'location' => [
      [
        [
          'param'    => 'post_type',
          'operator' => '==',
          'value'    => 'author',
        ],
      ],
    ],
    'menu_order'          => 0,
    'position'            => 'normal',
    'style'               => 'seamless',
    'label_placement'     => 'top',
    'instruction_placement' => 'label',
    'active'              => 1,
    'hide_on_screen'      => [
      0  => 'the_content',
      1  => 'excerpt',
      2  => 'discussion',
      3  => 'comments',
      5  => 'slug',
      6  => 'author',
      9  => 'categories',
      // 10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);
