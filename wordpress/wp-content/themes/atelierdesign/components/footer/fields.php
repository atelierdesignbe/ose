<?php

/**
 * ACF Fields for Footer
 */

$footerFields = [
  [
    'key' => 'field-footer-contact',
    'label' => 'Contact',
    'name' => 'contact',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-footer-contact-link',
        'label' => 'Link contact',
        'name' => 'link',
        'type' => 'link',
        'return_format' => 'array'
      ],
      [
        'key' => 'field-footer-contact-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
      ],
      [
        'key' => 'field-footer-contact-addr',
        'label' => 'Address',
        'name' => 'addr',
        'type' => 'textarea',
        'new_lines' => 'br',
      ],
      [
        'key' => 'field-footer-contact-email',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'text',
      ],
      [
        'key' => 'field-footer-contact-phone',
        'label' => 'Phone',
        'name' => 'phone',
        'type' => 'text',
      ],
    ]
  ],
  [
    'key' => 'field-footer-newsletter',
    'label' => 'Newsletter',
    'name' => 'newsletter',
    'type' => 'text',
    'instructions' => 'Form id from Formidable Form',
  ],
  [
    'key' => 'field-footer-menu',
    'label' => 'Menu',
    'name' => 'menu',
    'type' => 'relationship',
    'post_type' => ['page'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
  ],
  [
    'key' => 'field-footer-bottom-menu',
    'label' => 'Bottom Nav',
    'name' => 'bottom-nav',
    'type' => 'relationship',
    'post_type' => ['page'], // tu peux mettre ce que tu veux ici
    'filters' => ['search'], // filtres dans l’UI ACF
    'return_format' => 'object',
  ],
];

$footerFieldGroup = [
  'key' => 'field-group-footer',
  'title' => 'footer',
  'fields' => $footerFields,
];

acf_add_local_field_group($footerFieldGroup);
