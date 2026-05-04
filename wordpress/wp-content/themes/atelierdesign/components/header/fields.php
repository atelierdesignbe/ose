<?php

$headerFields = [
  [
    'key'   => 'field-header-menu-',
    'label' => 'Menu',
    'name'  => 'header-nav',
    'type'  => 'clone',
    'clone' => ['field-menu-group'],
    'prefix_name' => 1,
    'display' => 'group'
  ],

  [
    'key' => 'field-header-contact',
    'label' => 'Contact link',
    'type' => 'link',
    'name' => 'header-contact'
  ]
];

$headerFieldGroup = [
  'key' => 'field-group-header',
  'title' => 'header',
  'fields' => $headerFields,
];

acf_add_local_field_group($headerFieldGroup);
