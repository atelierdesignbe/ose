<?php

/**
 * ACF Fields for Footer
 */

$footerFields = [
  
];

$footerFieldGroup = [
  'key' => 'field-group-footer',
  'title' => 'footer',
  'fields' => $footerFields,
];

acf_add_local_field_group($footerFieldGroup);
