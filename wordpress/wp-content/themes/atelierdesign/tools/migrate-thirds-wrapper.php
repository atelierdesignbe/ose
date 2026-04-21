<?php
/**
 * One-shot migration: rename legacy `_wrapper` blocks nested inside
 * `_thirds` to `_thirdsColumn`.
 *
 * The pre-main ad-ui declared `_thirds` with `_wrapper` as its child
 * layout. Current ad-ui uses `_thirdsColumn` for the same role. Existing
 * content in the database still references `_wrapper`, so ACF can't
 * match it to a registered layout and silently drops it — the
 * right-column copy on pages like /about-us/, /themes/, /join-us/
 * disappears.
 *
 * Both layouts expose the same sub-field surface (span / divider / a
 * flexible "content" child), so renaming the layout key and sub-field
 * path is sufficient — no value transforms needed.
 *
 * Run via wp-cli:
 *   wp eval-file wp-content/themes/atelierdesign/tools/migrate-thirds-wrapper.php
 *
 * Idempotent.
 */

global $wpdb;

// Step 1: update serialized `_thirds` list meta values that enumerate children.
$listRows = $wpdb->get_results(
    "SELECT meta_id, post_id, meta_key, meta_value
       FROM {$wpdb->postmeta}
      WHERE meta_key LIKE '%\\_thirds'
         OR meta_key LIKE '%\\_thirds\\_side'"
);

$listsUpdated = 0;
foreach ($listRows as $row) {
    $value = $row->meta_value;
    if (strpos($value, 's:8:"_wrapper"') === false) {
        continue;
    }
    $newValue = str_replace('s:8:"_wrapper"', 's:13:"_thirdsColumn"', $value);
    if ($newValue === $value) {
        continue;
    }
    $wpdb->update(
        $wpdb->postmeta,
        ['meta_value' => $newValue],
        ['meta_id' => $row->meta_id]
    );
    $listsUpdated++;
}

// Step 2: rename `__thirds_N__wrapper…` meta keys to `__thirds_N__thirdsColumn…`.
// Only keys that sit inside a `_thirds` parent are touched — that's what the
// `%\\_thirds\\_%\\_wrapper` pattern guarantees.
$keyRows = $wpdb->get_results(
    "SELECT meta_id, meta_key
       FROM {$wpdb->postmeta}
      WHERE meta_key REGEXP '_thirds_[0-9]+__wrapper'"
);

$keysRenamed = 0;
$skippedCollisions = 0;
foreach ($keyRows as $row) {
    $oldKey = $row->meta_key;
    // Rename only the `_thirds_<n>__wrapper` segment, leaving any deeper
    // `_wrapper` elsewhere in the key untouched (in practice the row only
    // contains one such segment, but being explicit is safer).
    $newKey = preg_replace(
        '/(_thirds_\d+_)_wrapper/',
        '$1_thirdsColumn',
        $oldKey,
        1
    );

    if ($newKey === $oldKey) {
        continue;
    }

    // If the target key already exists for the same post, an earlier pass
    // already migrated it; skip instead of clobbering.
    $postIdForRow = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_id = %d",
        $row->meta_id
    ));
    $collision = $wpdb->get_var($wpdb->prepare(
        "SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s",
        $postIdForRow,
        $newKey
    ));
    if ($collision) {
        $skippedCollisions++;
        continue;
    }

    $wpdb->update(
        $wpdb->postmeta,
        ['meta_key' => $newKey],
        ['meta_id' => $row->meta_id]
    );
    $keysRenamed++;
}

echo "Thirds/wrapper migration: {$listsUpdated} lists updated, {$keysRenamed} keys renamed, {$skippedCollisions} collisions skipped.\n";
