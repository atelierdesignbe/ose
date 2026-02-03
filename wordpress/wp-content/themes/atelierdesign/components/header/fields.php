<?php

$headerFields = [
  [
    'key' => 'field-header-menu-nav',
    'label' => 'Menu',
    'type' => 'link',
    'name' => 'header-nav',
    'type' => 'relationship',
    'post_type' => ['page'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
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
