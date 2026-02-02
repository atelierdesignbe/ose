<?php

$heroFields = [
  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'text',
  ],
];

$heroFieldGroup = [
  'key' => 'field-group-hero',
  'title' => 'Hero',
  'fields' => $heroFields,
];

acf_add_local_field_group($heroFieldGroup);
