<?php

$eventsFields = [

];

$eventsFieldGroup = [
  'key' => 'field-group-events',
  'title' => 'Events',
  'fields' => $eventsFields,
];

acf_add_local_field_group($eventsFieldGroup);