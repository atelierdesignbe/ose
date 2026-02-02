<?php

/**
 * ACF Fields for Footer
 */

$footerFields = [
  [
    'key' => 'field-footer-nav',
    'label' => 'Navigation',
    'name' => 'footer_nav',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-footer-nav-items',
        'label' => 'Items',
        'name' => 'items',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add link',
        'sub_fields' => [
          [
            'key' => 'field-footer-nav-item-link',
            'label' => 'Select a link',
            'name' => 'link',
            'type' => 'link',
            'required' => 1,
          ],
        ],
      ],
    ],
  ],
  [
    'key' => 'field-footer-contact',
    'label' => 'Contact',
    'name' => 'footer_contact',
    'type' => 'group',
    'sub_fields' => [
      [
        'key' => 'field-footer-contact-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
      ],
      [
        'key' => 'field-footer-contact-content',
        'label' => 'Content',
        'name' => 'content',
        'type' => 'textarea',
        'new_lines' => 'br',
      ],
    ],
  ],
  [
    'key' => 'field-footer-socials',
    'label' => 'Socials',
    'name' => 'footer_socials',
    'type' => 'group',
    'sub_fields' => [
      // Title
      [
        'key' => 'field-footer-socials-title',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
      ],
      // Facebook
      [
        'key' => 'field-footer-socials-facebook',
        'label' => 'Facebook',
        'name' => 'facebook',
        'type' => 'url',
      ],
      // LinkedIn
      [
        'key' => 'field-footer-socials-linkedin',
        'label' => 'LinkedIn',
        'name' => 'linkedin',
        'type' => 'url',
      ],
      // Instagram
      [
        'key' => 'field-footer-socials-instagram',
        'label' => 'Instagram',
        'name' => 'instagram',
        'type' => 'url',
      ],
    ],
  ],
  [
    'key' => 'field-footer-policies',
    'label' => 'Policies',
    'name' => 'footer_policies',
    'type' => 'group',
    'layout' => 'block',
    'sub_fields' => [
      [
        'key' => 'field-footer-policies-items',
        'label' => '',
        'name' => 'items',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add link',
        'sub_fields' => [
          [
            'key' => 'field-footer-policies-item-link',
            'label' => '',
            'name' => 'link',
            'type' => 'link',
            'required' => 1,
          ],
        ],
      ],
    ],
  ],
];

$footerFieldGroup = [
  'key' => 'field-group-footer',
  'title' => 'footer',
  'fields' => $footerFields,
];

acf_add_local_field_group($footerFieldGroup);
