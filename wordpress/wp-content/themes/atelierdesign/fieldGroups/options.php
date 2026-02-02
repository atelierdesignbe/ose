<?php

acf_add_local_field_group([
  'key' => 'options-page',
  'title' => 'General Settings & Global Sections',
  'fields' => [
    // Tab: Header
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
