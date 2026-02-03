<?php

$heroFields = [
  [
    'key' => 'field-hero-type',
    'label' => 'Type',
    'name' => 'type',
    'type' => 'true_false',
    'ui' => 1,
    'ui_on_text' => 'Column',
    'ui_off_text' => 'Fullsize',
    'default_value' => 0,
  ],
  [
    'key' => 'field-hero-label',
    'label' => 'Label',
    'name' => 'label',
    'type' => 'text',
    // 'rows' => 1,
    // 'required' => true,
    // 'new_lines' => 'br'
  ],
  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    'required' => true,
    'new_lines' => 'br'
  ],
  [
    'key' => 'field-hero-title',
    'label' => 'Title',
    'name' => 'title',
    'type' => 'textarea',
    'rows' => 1,
    'required' => true,
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
    'key' => 'field-home-hero-link-first',
    'label' => 'First Link',
    'name' => 'first-link',
    'type' => 'link',
  ],
  [
    'key' => 'field-home-hero-link-last',
    'label' => 'Second Link',
    'name' => 'last-link',
    'type' => 'link',
  ],

  [
    'key' => 'field-hero-media-image',
    'label' => 'Cover',
    'name' => 'cover',
    'type' => 'image',
    'preview_size' => 'thumbnail',
    'library' => 'all',
    'mime_types' => 'jpg,jpeg,png,svg,webp',
  ],
];

$heroFieldGroup = [
  'key' => 'field-group-hero',
  'title' => 'Hero',
  'fields' => $heroFields,
];

acf_add_local_field_group($heroFieldGroup);