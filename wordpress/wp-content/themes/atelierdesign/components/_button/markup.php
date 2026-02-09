<?php

$style = 'button-' . $args['layout_settings']['style'];
$color = 'button-' . $args['layout_settings']['color'];

$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}

$alignmentClass = 'text-left';
if (isset($args['layout_settings']['alignment'])) {
  switch ($args['layout_settings']['alignment']) {
    case 'left':
      $alignmentClass = 'text-left';
      break;
    case 'center':
      $alignmentClass = 'text-center';
      break;
    case 'right':
      $alignmentClass = 'text-right';
      break;
  }
}

?>
<?php if (isset($args['link']['url']) && !empty($args['link']['url'])) : ?>
  <div class="button-wrapper my-elem-sm <?= $isFullWidth ? '' : 'px-content' ?> <?= $alignmentClass; ?> autoscale-children aos animate-fadeinup">
    <a href="<?= $args['link']['url'] ?? '#'; ?>" <?= isset($args['link']['target']) && !empty($args['link']['target']) ? 'target="' . $args['link']['target'] . '"' : ''; ?> class="<?= $style; ?> <?= $color; ?> transition-colors duration-300 ease-out-cubic">
      <span class="button-title">
        <?= $args['link']['title'] ?? ''; ?>
      </span>
      <!-- <svg class="button-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 34 34">
        <path fill="currentColor" fill-rule="evenodd" d="M17 0a17 17 0 1 1 0 34 17 17 0 0 1 0-34Zm-1 12 4.1 4.3H11v1.4h9L16 22l1.1 1 6-6-6-6-1 1Z" clip-rule="evenodd" />
      </svg> -->
    </a>
  </div>
<?php endif; ?>