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
      [
        'key'   => 'field-single-member-tab-info',
        'label' => 'Info',
        'type'  => 'tab',
        'no_preference' => 0,
      ],
      [
        'key'   => 'field-single-member-role',
        'label' => 'Role / Position',
        'name'  => 'role',
        'type'  => 'text',
      ],
      [
        'key'   => 'field-single-member-bio',
        'label' => 'Bio',
        'name'  => 'bio',
        'type'  => 'textarea',
        'rows'  => 4,
      ],
      [
        'key'          => 'field-single-member-email',
        'label'        => 'Email',
        'name'         => 'email',
        'type'         => 'email',
      ],

      // Tab: Social
      [
        'key'   => 'field-single-member-tab-social',
        'label' => 'Social',
        'type'  => 'tab',
        'no_preference' => 0,
      ],
      [
        'key'   => 'field-single-member-linkedin',
        'label' => 'LinkedIn',
        'name'  => 'linkedin',
        'type'  => 'url',
      ],
      [
        'key'   => 'field-single-member-twitter',
        'label' => 'Twitter / X',
        'name'  => 'twitter',
        'type'  => 'url',
      ],

      // Tab: Publications liées
      [
        'key'   => 'field-single-member-tab-publications',
        'label' => 'Publications',
        'type'  => 'tab',
        'no_preference' => 0,
      ],
      [
        'key'           => 'field-single-member-show-publications',
        'label'         => 'Show related publications',
        'name'          => 'show_publications',
        'type'          => 'true_false',
        'default_value' => 1,
        'ui'            => 1,
      ],

    ],
    'location' => [
      [
        [
          'param'    => 'post_type',
          'operator' => '==',
          'value'    => 'member',
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
      10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);
