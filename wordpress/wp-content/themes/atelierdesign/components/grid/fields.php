<?php

/**
 * ACF Fields for CTA CARDS
 */

$blockGridFields = [
    // WYSIWYG
  [
    ...wysiwyg('field-grid-content'),
    'name' => 'grid-content',
    'label' => 'Content'
  ],
  [
    'key' => 'field-grid-content-link',
    'label' => 'Link',
    'type' => 'link',
    'name' => 'grid-link',
  ],
  [
    'key' => 'field-grid-images',
    'label' => 'Images',
    'name' => 'grid-images',
    'type' => 'group',
    'layout' => 'row',
    'sub_fields' => [
      [
        'key' => 'field-grid-image-top',
        'label' => 'Image top',
        'name' => 'image-top',
        'type' => 'image',
        'required' => true
      ],
      [
        'key' => 'field-grid-image-center',
        'label' => 'Image center',
        'name' => 'image-center',
        'type' => 'image',
        'required' => true
      ],
      [
        'key' => 'field-grid-image-bottom',
        'label' => 'Image bottom',
        'name' => 'image-bottom',
        'type' => 'image',
        'required' => true
      ],
    ]
  ],
  [
    ...wysiwyg('field-grid-content-middle-left'),
    'label' => 'Content middle left',
    'name' => 'grid-content-middle-left',
  ],

  [
    ...wysiwyg('field-grid-content-middle-right'),
    'label' => 'Content middle right',
    'name' => 'grid-content-middle-right',
  ],

  [
    ...wysiwyg('field-grid-content-bottom-center'),
    'label' => 'Content bottom center',
    'name' => 'grid-content-bottom-center',
  ],

  [
    ...wysiwyg('field-grid-content-bottom-right'),
    'label' => 'Content middle right',
    'name' => 'grid-content-bottom-right',
  ],
  
];

$blockGridFieldGroup = [
  'key' => 'field-group-grid',
  'title' => 'Block grid',
  'fields' => $blockGridFields,
];

acf_add_local_field_group($blockGridFieldGroup);
