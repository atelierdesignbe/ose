<?php

$layoutSettings = $args['layout_settings'] ?? [];
$direction = $layoutSettings['direction'] ?? 'row';
$isFullWidth = !empty($layoutSettings['isFullWidth']);
$fullWidthItems = !empty($layoutSettings['fullWidthItems']);

// Desktop alignment — read from direction-specific field
if ($direction === 'column') {
  $desktopAlignSetting = $layoutSettings['horizontalAlign'] ?? 'start';
} else {
  $desktopAlignSetting = $layoutSettings['justify'] ?? 'start';
}

// Mobile settings (inherit = use desktop value)
$mobileDirection = $layoutSettings['mobileDirection'] ?? 'inherit';
if ($mobileDirection === 'inherit') {
  $mobileDirection = $direction;
}
$mobileFullWidthItems = $layoutSettings['mobileFullWidthItems'] ?? 'inherit';
if ($mobileFullWidthItems === 'inherit') {
  $mobileFullWidthItems = $fullWidthItems;
} else {
  $mobileFullWidthItems = $mobileFullWidthItems === 'yes';
}

if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

// Responsive direction classes
$desktopDirClass = $direction === 'column' ? 'md:flex-col' : 'md:flex-row';
$mobileDirClass = $mobileDirection === 'column' ? 'max-md:flex-col' : 'max-md:flex-row';

$wrapClass = 'max-md:flex-wrap md:flex-nowrap';

// Desktop alignment (md: prefix) — depends on desktop direction
if ($direction === 'column') {
  $desktopAlignClass = match ($desktopAlignSetting) {
    'center' => 'md:items-center',
    'end' => 'md:items-end',
    default => 'md:items-start',
  };
  $desktopJustifyClass = '';
} else {
  $desktopAlignClass = 'md:items-center';
  $desktopJustifyClass = match ($desktopAlignSetting) {
    'center' => 'md:justify-center',
    'end' => 'md:justify-end',
    default => 'md:justify-start',
  };
}

// Mobile alignment (max-md: prefix) — read from direction-specific field
if ($mobileDirection === 'column') {
  $mobileAlignSetting = $layoutSettings['mobileHorizontalAlign'] ?? 'inherit';
  if ($mobileAlignSetting === 'inherit') {
    $mobileAlignSetting = $desktopAlignSetting;
  }
  $mobileAlignClass = match ($mobileAlignSetting) {
    'center' => 'max-md:items-center',
    'end' => 'max-md:items-end',
    default => 'max-md:items-start',
  };
  $mobileJustifyClass = '';
} else {
  $mobileJustifySetting = $layoutSettings['mobileJustify'] ?? 'inherit';
  if ($mobileJustifySetting === 'inherit') {
    $mobileJustifySetting = $desktopAlignSetting;
  }
  $mobileAlignClass = 'max-md:items-center';
  $mobileJustifyClass = match ($mobileJustifySetting) {
    'center' => 'max-md:justify-center',
    'end' => 'max-md:justify-end',
    default => 'max-md:justify-start',
  };
}

$wrapperClasses = array_filter([
  'buttons-wrapper',
  $fullWidthItems ? 'buttons-full-width-items' : '',
  'my-elem-sm',
  'flex',
  $desktopDirClass,
  $mobileDirClass,
  $wrapClass,
  $desktopJustifyClass,
  $desktopAlignClass,
  $mobileJustifyClass,
  $mobileAlignClass,
  'gap-sm',
  $isFullWidth ? '' : 'mx-content',
  "autoscale-children",
  'aos animate-fadeinup',
]);

$items = $args['items'] ?? [];

?>
<?php if (!empty($items) && is_array($items)) : ?>
  <div class="<?= esc_attr(trim(implode(' ', $wrapperClasses))) ?>">
    <?php foreach ($items as $item) : ?>
      <?php
      $link = $item['_button_link'] ?? [];
      $url = $link['url'] ?? '';
      if (empty($url)) continue;
      $title = $link['title'] ?? '';
      $target = $link['target'] ?? '';
      $style = isset($item['style']) && $item['style'] !== ''
        ? 'button-' . toKebabCase($item['style'])
        : '';
      ?>
      <?php
      $itemClasses = "$style transition-colors duration-300 ease-out-cubic";
      if ($fullWidthItems) {
        $itemClasses .= $direction === 'row'
          ? ' buttons-full-width-item'
          : ' md:grow md:w-full';
      }
      if ($mobileFullWidthItems) {
        $itemClasses .= $mobileDirection === 'row'
          ? ' max-md:grow max-md:shrink-0 max-md:basis-auto max-md:max-w-full max-md:self-stretch'
          : ' max-md:grow max-md:w-full';
      }
      ?>
      <a href="<?= esc_url($url) ?>" <?= !empty($target) ? 'target="' . esc_attr($target) . '"' : '' ?> class="<?= $itemClasses ?>">
        <span class="button-title">
          <?= esc_html($title) ?>
        </span>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
