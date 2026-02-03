<?php 

$socialFields = [
  [
    'key' => 'field-social-nav',
    'label' => 'Social',
    'name' => 'social',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field_social-nav-items',
        'label' => 'Social',
        'name' => 'items',
        'type' => 'repeater',
        'sub_fields' => [
          [
            'key' => 'field_social-nav-item-type',
            'label' => 'Type',
            'name' => 'type',
            'type' => 'select',
            'choices' => [
              'instagram' => 'Instagram',
              'linkedin' => 'Linkedin',
              'facebook' => 'Facebook',
              'twitter' => 'Twitter',
              'tiktok' => 'Tiktok',
              'youtube' => 'Youtube',
            ],
          ],
          [
            'key' => 'field_social-nav-item-url',
            'label' => 'Url',
            'name' => 'url',
            'type' => 'url',
          ]
        ]
      ]
    ],
  ],
];


$socialFieldGroup = [
  'key' => 'field-group-social',
  'title' => 'Social',
  'fields' => $socialFields,
];

acf_add_local_field_group($socialFieldGroup);
