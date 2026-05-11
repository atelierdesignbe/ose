<?php

/**
 * ACF Fields for Timeline
 */

global $adwp;

$icon = '<svg style="vertical-align: bottom;" stroke="currentColor" stroke-width="0" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 16 16" " xmlns="http://www.w3.org/2000/svg"><path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"></path></svg>';

$fieldKey = 'field-contact';

// $email = adui_options('form-contact-email');
// var_dump($email);

$contactFields = [
  [
    ...wysiwyg('field-contact-content'),
    'name' => 'content',
    'label' => 'Content'
  ],
  [
    'key' => 'field-contact-formidable',
    'label' => 'Select form',
    'name' => 'form_id',
    'type' => 'select',
    'choices' => [], // sera rempli dynamiquement
    'ui' => 1,
    'ajax' => 0,
  ],
  [
    'key' => 'field-contact-more-email-items',
    'label' => 'Add other recipients emails ',
    'name' => 'contact-email',
    'type' => 'repeater',
    'sub_fields' => [
      [
        'key' => 'field-contact-more-email',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
      ]
    ]
  ],
];


$contactLayout = [
  'key'     => 'layout-contact',
  'label'   => $icon . ' Form',
  'name'    => 'form',
  'display' => 'block',
  'sub_fields' => $contactFields,
];

$adwp->add_block_layout('layoutContact', $contactLayout, 99);

$adwp->register_layout('form', $contactLayout);