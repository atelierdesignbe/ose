<?php

$projectsFields = [

];

$projectsFieldGroup = [
  'key' => 'field-group-projects',
  'title' => 'Projects',
  'fields' => $projectsFields,
];

acf_add_local_field_group($projectsFieldGroup);