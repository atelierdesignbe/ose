<?php

/**
 * ACF fields for Team page template
 */

add_action('acf/include_fields', function () {
  acf_add_local_field_group([
    'key'    => 'field-group-custom-team-template',
    'title'  => 'Custom Fields — Team',
    'fields' => [

      // Tab: Hero
      [
        'key'   => 'field-custom-team-template-tab-hero',
        'label' => 'Hero',
        'type'  => 'tab',
        'no_preference' => 0,
      ],
      [
        'key'        => 'field-custom-team-template-group-hero',
        'label'      => 'Hero',
        'name'       => 'hero',
        'type'       => 'group',
        'sub_fields' => [
          [
            'key'   => 'field-custom-team-template-clone-fieldgroup-hero',
            'label' => 'Hero',
            'name'  => 'hero',
            'type'  => 'clone',
            'clone' => [
              0 => 'field-group-hero',
            ],
          ],
        ],
      ],
      [
        'key' => 'field-custom-team-template-tab-flexible-before',
        'label' => 'Content before team',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // Flexible content section
      [
        'key' => 'field-custom-team-template-clone-fieldgroup-flexible-before',
        'label' => 'Flexible Content',
        'name' => 'flexible_content_before',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
        'prefix_name' => 1,
        'display' => 'group'
    
      ],
      [
        'key' => 'field-custom-team-template-tab-flexible',
        'label' => 'Flexible Content',
        'type' => 'tab',
        'no_preference' => 0,
      ],
      // Flexible content section
      [
        'key' => 'field-custom-team-template-clone-fieldgroup-flexible',
        'label' => 'Flexible Content',
        'name' => 'flexible_content',
        'type' => 'clone',
        'clone' => [
          0 => 'field-flexible-flexible-layout',
        ],
        'prefix_name' => 1,
        'display' => 'group'
    
      ],
      // Tab: CTA Footer
      [
        'key'   => 'field-custom-team-cta-footer-tab',
        'label' => 'CTA Footer',
        'type'  => 'tab',
        'no_preference' => 0,
      ],
      [
        'key'           => 'field-custom-team-cta-footer-state',
        'label'         => 'CTA status',
        'name'          => 'cta_status',
        'type'          => 'button_group',
        'choices'       => [
          'default'  => 'Default',
          'override' => 'Override',
          'disabled' => 'Disabled',
        ],
        'default_value' => 'default',
        'layout'        => 'horizontal',
        'return_format' => 'value',
      ],
      [
        'key'              => 'field-custom-team-cta-footer-clone',
        'label'            => '',
        'name'             => 'cta',
        'type'             => 'clone',
        'clone'            => [
          0 => 'field-group-cta-footer',
        ],
        'display'          => 'group',
        'layout'           => 'block',
        'required'         => 0,
        'conditional_logic' => [
          [
            [
              'field'    => 'field-custom-team-cta-footer-state',
              'operator' => '==',
              'value'    => 'override',
            ],
          ],
        ],
      ],
    ],
    'location' => [
      [
        [
          'param'    => 'page_template',
          'operator' => '==',
          'value'    => 'templates/team.php',
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
      8  => 'featured_image',
      9  => 'categories',
      10 => 'tags',
      11 => 'send-trackbacks',
    ],
  ]);
}, 1000);


/**
 * Sur la page team template, les deux clones de flexible content n'ont pas de min.
 * Le min:1 vient de la source (field-flexible-flexible-layout dans flexible.php).
 * On l'écrase via acf/load_field uniquement sur la page qui utilise ce template.
 */
add_filter( 'acf/load_field/key=field-flexible-flexible-layout', function ( $field ) {
    if ( ! is_admin() ) return $field;

    $post_id = (int) ( $_GET['post'] ?? $_POST['post_ID'] ?? 0 );
    if ( ! $post_id ) return $field;

    $template = get_post_meta( $post_id, '_wp_page_template', true );
    if ( $template === 'templates/team.php' ) {
        $field['min'] = 0;
    }

    return $field;
} );
