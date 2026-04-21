<?php
$style = isset($args['layout_settings']['style']) && $args['layout_settings']['style'] !== ''
  ? 'icon-' . toKebabCase($args['layout_settings']['style'])
  : '';

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignmentMap = [
  'left' => 'justify-start',
  'center' => 'justify-center',
  'right' => 'justify-end',
];
// IMPORTANT: md:* variants are spelled out as literals so Tailwind's content
// scanner picks them up — a `str_replace` or `'md:' . $class` wouldn't be seen.
$mdAlignmentMap = [
  'left' => 'md:justify-start',
  'center' => 'md:justify-center',
  'right' => 'md:justify-end',
];

$desktopAlignment = $args['layout_settings']['alignment'] ?? 'left';
$desktopAlignmentClass = $alignmentMap[$desktopAlignment] ?? 'justify-start';
$desktopAlignmentMdClass = $mdAlignmentMap[$desktopAlignment] ?? 'md:justify-start';
$mobileAlignment = $args['layout_settings']['alignmentMobile'] ?? 'auto';

if ($mobileAlignment === 'inherit') {
  $mobileAlignment = 'auto';
}

if ($mobileAlignment === 'auto') {
  $alignment = $desktopAlignmentClass;
} else {
  $mobileAlignmentClass = $alignmentMap[$mobileAlignment] ?? $desktopAlignmentClass;
  $alignment = $mobileAlignmentClass . ' ' . $desktopAlignmentMdClass;
}

// OSE patch: acf-material-symbols returns an array only for icons present
// in its bundled dataset; anything outside (e.g. legacy Material Icons
// names like `gpp_good`, `emoji_events`, `insights`) comes back as the
// raw string. The Material Symbols webfont still renders those glyphs,
// so pass the name through instead of dropping the whole icon.
$iconName = is_array($args['icon'] ?? null)
  ? ($args['icon']['name'] ?? '')
  : ($args['icon'] ?? '');
?>
<?php if (!empty($iconName)): ?>
  <div class="icon-wrapper my-elem-sm flex <?= $alignment; ?> <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children aos animate-fadeinup">
    <div class="<?= $style; ?>">
      <span class="material-symbols-outlined">
        <?= esc_html($iconName); ?>
      </span>
    </div>
  </div>
<?php endif; ?>
