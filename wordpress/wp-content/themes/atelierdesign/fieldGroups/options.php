<?php

acf_add_local_field_group([
  'key' => 'options-page',
  'title' => 'General Settings & Global Sections',
  'fields' => [
    // Tab: Header
    [
      'key' => 'field-global-tab',
      'label' => 'Global',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-global-logo',
      'label' => 'Logo',
      'name' => 'logo',
      'type' => 'image',
      'preview_size' => 'thumbnail',
      'library' => 'all',
      'mime_types' => 'jpg,jpeg,png,svg,webp',
      'required' => 1
    ],
    [
      'key' => 'field-header-tab',
      'label' => 'Header',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    // Clone: field-group-header
    [
      'key' => 'field-header-clone',
      'label' => '',
      'name' => 'header',
      'type' => 'clone',
      'clone' => [
        0 => 'field-group-header',
      ],
      'display' => 'seamless',
      'layout' => 'block',
      'required' => 0,
      'conditional_logic' => 0,
    ],
    // Tab: CTA FOOTER
    [
      'key' => 'field-cta-footer-tab',
      'label' => 'CTA Footer',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-cta-footer-clone',
      'label' => '',
      'name' => 'cta',
      'type' => 'clone',
      'clone' => [
        0 => 'field-group-cta-footer',
      ],
      'display' => 'seamless',
      'layout' => 'block',
      'required' => 0,
      'conditional_logic' => 0,
    ],
    // Tab: Footer
    [
      'key' => 'field-footer-tab',
      'label' => 'Footer',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    // Clone: field-group-footer
    [
      'key' => 'field-footer-clone',
      'label' => '',
      'name' => 'footer',
      'type' => 'clone',
      'clone' => [
        0 => 'field-group-footer',
      ],
      'display' => 'seamless',
      'layout' => 'block',
      'required' => 0,
      'conditional_logic' => 0,
    ],
    [
      'key' => 'field-social-tab',
      'label' => 'Social',
      'type' => 'tab',
      'no_preference' => 0,
    ],
    [
      'key' => 'field-social-clone',
      'label' => '',
      'name' => 'social',
      'type' => 'clone',
      'clone' => [
        0 => 'field-group-social',
      ],
      'display' => 'seamless',
      'layout' => 'block',
      'required' => 0,
      'conditional_logic' => 0,
    ],
    [
      'key' => 'field-related-tab',
      'label' => 'Related News',
      'type' => 'tab',
      'no_preference' => 0,
    ],
  ],
  'location' => array(
    array(
      array(
        'param' => 'options_page',
        'operator' => '==',
        'value' => 'acf-options-global-fields',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'seamless',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'active' => true,
  'show_in_rest' => 0,
]);
