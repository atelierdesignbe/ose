<?php

$heroFields = [

  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    'required' => 1,
    'new_lines' => 'br'
  ],
  [
    'key' => 'field-hero-content',
    'label' => 'Content',
    'name' => 'content',
    'type' => 'textarea',
    'rows' => 2,
  ],
  [
    'key' => 'field-hero-media-image',
    'label' => 'Cover',
    'name' => 'cover',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'mime_types' => 'jpg,jpeg,png,svg,webp',
    'required' => 1,
  ],
];

$heroFieldGroup = [
  'key' => 'field-group-home-hero',
  'title' => 'Hero',
  'fields' => $heroFields,
];

acf_add_local_field_group($heroFieldGroup);