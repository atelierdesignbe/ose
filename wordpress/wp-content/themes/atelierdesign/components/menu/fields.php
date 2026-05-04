<?php

global $adwp, $adui_tokens;

require_once 'helper.php';

$fieldKey = 'field-menu';

// ── Field group ────────────────────────────────────────────────────────────────
$menuFieldGroup = [
  'key'    => $fieldKey . '-group',
  'title'  => 'Menu',
  'fields' => [
    [
      'key'          => $fieldKey . '-items',
      'label'        => 'Nav items',
      'name'         => 'items',
      'type'         => 'flexible_content',
      'min'          => 0,
      // 'max'          => ,
      'button_label' => 'Add nav item',
      'acfe_flexible_layouts_default' => ['nav_item'],
      'acfe_flexible_copy_paste'        => 1,   // bouton "Copier la disposition"
      'acfe_flexible_clone'             => 1,   // bouton "Dupliquer"

      // ✅ Activer le système Hidden/Visible sur les layouts
      'acfe_flexible_layouts_hidden'    => 1,
      'acfe_flexible_action_button'     => 0,
      'acfe_flexible_add_actions' => 0,

      'layouts'      => [
        [
          'key'        => $fieldKey . '-l0-nav-item',
          'name'       => 'nav_item',
          'label'      => 'Nav item',
          'display'    => 'block',
          'sub_fields' => menu_build_fields( $fieldKey, 1, 0, true ),
        ],
      ],
    ],
  ],
];

acf_add_local_field_group( $menuFieldGroup );
