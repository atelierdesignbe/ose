<?php

$heroHomeFields = [

  [
    'key' => 'field-home-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    'required' => 1,
    'new_lines' => 'br'
  ],
  [
    'key' => 'field-home-hero-content',
    'label' => 'Content',
    'name' => 'content',
    'type' => 'textarea',
    'rows' => 2,
  ],
  [
    'key' => 'field-home-hero-media-image',
    'label' => 'Cover',
    'name' => 'cover',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'mime_types' => 'jpg,jpeg,png,svg,webp',
    'required' => 1,
  ],
];

$heroHomeFieldGroup = [
  'key' => 'field-group-home-hero',
  'title' => 'Hero',
  'fields' => $heroHomeFields,
];

acf_add_local_field_group($heroHomeFieldGroup);