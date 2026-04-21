<?php

global $adwp, $adui_tokens;

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$responsiveSizingModes = array_keys($adui_tokens['responsiveSizing'] ?? []);
$defaultSizingMode = $responsiveSizingModes[0] ?? null;
$iconSizingTokens = $defaultSizingMode ? ($adui_tokens['responsiveSizing'][$defaultSizingMode]['icon'] ?? []) : [];
$styleName = $args['layout_settings']['style'] ?? '';
$style = $styleName !== '' ? 'icon-' . toKebabCase($styleName) : '';
$isSmallStyle = $styleName !== '' && !empty($iconSizingTokens[$styleName]['isSmall']);

// Desktop settings
$desktopDirection = $args['layout_settings']['direction'] ?? 'aside';
if ($isSmallStyle) {
  $desktopDirection = 'aside';
}
$desktopIsAside = $desktopDirection === 'aside';
$desktopVAlign = $args['layout_settings']['alignItems'] ?? 'start';
$smallVAlign = $args['layout_settings']['smallAlignItems'] ?? 'start';
$desktopHAlign = $args['layout_settings']['horizontalAlign'] ?? 'left';

// Mobile settings
$mobileDirection = $args['layout_settings']['directionMobile'] ?? 'under';
if ($isSmallStyle) {
  $mobileDirection = 'aside';
}
$mobileIsAside = $mobileDirection === 'aside';

$mobileVAlignRaw = $args['layout_settings']['mobileVerticalAlign'] ?? 'auto';
$mobileHAlignRaw = $args['layout_settings']['mobileHorizontalAlign'] ?? 'auto';

// Resolve "auto": inherit desktop alignment when directions match, otherwise use component default
$mobileVAlign = $mobileVAlignRaw === 'auto'
  ? ($desktopDirection === $mobileDirection ? $desktopVAlign : 'start')
  : $mobileVAlignRaw;
$mobileHAlign = $mobileHAlignRaw === 'auto'
  ? ($desktopDirection === $mobileDirection ? $desktopHAlign : 'left')
  : $mobileHAlignRaw;

if ($isSmallStyle) {
  $desktopVAlign = $smallVAlign;
  $mobileVAlign = $smallVAlign;
}

// Direction classes
$directionClasses = '';
if (!$desktopIsAside) {
  $directionClasses .= 'md:flex-col ';
}
if (!$mobileIsAside) {
  $directionClasses .= 'max-md:flex-col ';
}

// Desktop alignment (md: prefix)
$desktopAlignClass = '';
if ($desktopIsAside) {
  $desktopAlignClass = match ($desktopVAlign) {
    'center' => 'md:items-center',
    'end' => 'md:items-end',
    default => 'md:items-start',
  };
} else {
  $desktopAlignClass = match ($desktopHAlign) {
    'center' => 'x-content-align-md-center md:[&_.wysiwyg>*]:!text-center md:[&_.wysiwyg>hr]:![margin-inline:auto] md:[&_.button-wrapper]:!text-center md:[&_.buttons-wrapper]:!justify-center md:[&_.badge-wrapper]:!text-center md:[&_.badges-wrapper]:!justify-center',
    'right' => 'x-content-align-md-right md:[&_.wysiwyg>*]:!text-right md:[&_.wysiwyg>hr]:![margin-left:auto] md:[&_.wysiwyg>hr]:![margin-right:0] md:[&_.button-wrapper]:!text-right md:[&_.buttons-wrapper]:!justify-end md:[&_.badge-wrapper]:!text-right md:[&_.badges-wrapper]:!justify-end',
    default => '',
  };
}

// Mobile alignment (max-md: prefix)
$mobileAlignClass = '';
if ($mobileIsAside) {
  $mobileAlignClass = match ($mobileVAlign) {
    'center' => 'max-md:items-center',
    'end' => 'max-md:items-end',
    default => 'max-md:items-start',
  };
} else {
  $mobileAlignClass = match ($mobileHAlign) {
    'center' => 'x-content-align-max-md-center max-md:[&_.wysiwyg>*]:!text-center max-md:[&_.wysiwyg>hr]:![margin-inline:auto] max-md:[&_.button-wrapper]:!text-center max-md:[&_.buttons-wrapper]:!justify-center max-md:[&_.badge-wrapper]:!text-center max-md:[&_.badges-wrapper]:!justify-center',
    'right' => 'x-content-align-max-md-right max-md:[&_.wysiwyg>*]:!text-right max-md:[&_.wysiwyg>hr]:![margin-left:auto] max-md:[&_.wysiwyg>hr]:![margin-right:0] max-md:[&_.button-wrapper]:!text-right max-md:[&_.buttons-wrapper]:!justify-end max-md:[&_.badge-wrapper]:!text-right max-md:[&_.badges-wrapper]:!justify-end',
    default => '',
  };
}

// When the layout stacks "under", align the icon wrapper itself on the horizontal axis.
$desktopIconAlignClass = '';
if (!$desktopIsAside) {
  $desktopIconAlignClass = match ($desktopHAlign) {
    'center' => 'md:self-center',
    'right' => 'md:self-end',
    default => '',
  };
}

$mobileIconAlignClass = '';
if (!$mobileIsAside) {
  $mobileIconAlignClass = match ($mobileHAlign) {
    'center' => 'max-md:self-center',
    'right' => 'max-md:self-end',
    default => '',
  };
}

// Gap: fixed value (0.5 for small icons, 1 for other variants)
$gapSize = $isSmallStyle ? 0.5 : 1;
$gapStyle = '--gap-x-unit: ' . $gapSize . '; --gap-y-unit: ' . $gapSize . '; column-gap: var(--tw-flex-grid-gap-x); row-gap: var(--tw-flex-grid-gap-y);';

$isNested = isset($args['isNested']) && $args['isNested'] == true;
$marginClass = ($isFullWidth && !$isNested) ? 'my-elem-xl' : 'my-elem-md';

?>
<div class="group-wrapper <?= $marginClass ?> flex <?= $directionClasses ?>custom-flex-grid-gap-x custom-flex-grid-gap-y <?= $desktopAlignClass ?> <?= $mobileAlignClass ?> <?= $isFullWidth ? '' : 'px-content' ?> aos animate-fadeinup aos-disable-children" style="<?= $gapStyle ?>">
  <div class="icon-wrapper flex shrink-0 autoscale-children aos animate-fadeinup <?= $desktopIconAlignClass ?> <?= $mobileIconAlignClass ?>">
    <?php
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
      <div class="<?= $style; ?>">
        <span class="material-symbols-outlined">
          <?= esc_html($iconName); ?>
        </span>
      </div>
    <?php endif; ?>
  </div>
  <div class="inline-flexible min-w-0 grow">
    <?php $adwp->render_flexible_layout($args['_iconGroup'], [
      'parentColor' => isset($args['parentColor']) && $args['parentColor'] != '' ? $args['parentColor'] : null,
      'isNested' => true,
      'layout_settings' => [
        'isFullWidth' => false,
      ],
    ]); ?>
  </div>
</div>
