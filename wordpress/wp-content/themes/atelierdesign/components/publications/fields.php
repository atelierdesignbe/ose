<?php

$publicationsFields = [

];

$publicationsFieldGroup = [
  'key' => 'field-group-publications',
  'title' => 'Publications',
  'fields' => $publicationsFields,
];

acf_add_local_field_group($publicationsFieldGroup);