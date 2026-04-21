<?php
/**
 * One-shot migration: collapse legacy icon ACF fields (`color` + `hasBackground`)
 * into the current ad-ui combined `style` field.
 *
 * Old ad-ui stored each icon instance as:
 *   layout_settings_color         = "primary"
 *   layout_settings_hasBackground = 1
 * and emitted classes `icon-primary icon-has-background`.
 *
 * Current ad-ui expects:
 *   layout_settings_style = "primaryHasBackground"
 * and emits a single combined class `icon-primary-has-background`.
 *
 * Run via wp-cli:
 *   wp eval-file wp-content/themes/atelierdesign/tools/migrate-icon-style.php
 *
 * Idempotent: skips rows where `_style` is already populated.
 */

global $wpdb;

$rows = $wpdb->get_results("
  SELECT pm.post_id, pm.meta_key, pm.meta_value AS color,
    (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = pm.post_id AND meta_key = CONCAT(SUBSTRING(pm.meta_key, 1, CHAR_LENGTH(pm.meta_key) - 6), '_hasBackground') LIMIT 1) AS has_bg
  FROM {$wpdb->postmeta} pm
  INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
  WHERE pm.meta_key LIKE '%layout_settings_color'
    AND p.post_status = 'publish'
    AND p.post_type IN ('page', 'project', 'publication', 'event')
");

$migrated = 0;
$skipped  = 0;

foreach ($rows as $row) {
    $prefix   = substr($row->meta_key, 0, -strlen('_color'));
    $styleKey = $prefix . '_style';

    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
        $row->post_id,
        $styleKey
    ));

    if ($existing !== null && $existing !== '') {
        $skipped++;
        continue;
    }

    $styleValue = $row->color;
    if (!empty($row->has_bg) && $row->has_bg !== '0') {
        $styleValue .= 'HasBackground';
    }

    update_post_meta($row->post_id, $styleKey, $styleValue);
    update_post_meta($row->post_id, '_' . $styleKey, 'field-icon-setting-style');
    $migrated++;
}

echo "Icon style migration: {$migrated} migrated, {$skipped} skipped.\n";
