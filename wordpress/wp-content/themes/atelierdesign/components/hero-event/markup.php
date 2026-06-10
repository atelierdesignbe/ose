<?php
$override = $args['hero-override'] ?? null;
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$coverState = get_field('cover-status') ?? 'fill';

$date_start = get_field('date_start');
$date_end   = get_field('date_end');
$coverState = get_field('cover-status') ?? 'fill';
$size = get_field('size') ?? 'fullscreen';

$ts_start = DateTime::createFromFormat('d-m-Y', $date_start)->getTimestamp();

if ($date_end) {
  $ts_end = DateTime::createFromFormat('d-m-Y', $date_end)->getTimestamp();
}

$types = get_the_terms( get_the_ID(), 'event_type' );
$themes = get_the_terms( get_the_ID(), 'themes' );

if (!$cover) $coverState = 'none';



ob_start();
?>
<div class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup autoscale-children">
  <?php if($date_start): ?> <span class="badge badge-primary badge-outlined"><?= $date_start ?></span><?php endif; ?>
  <?php if($date_end && $ts_end > $ts_start): ?><span><?= icon('chevron', (isset($theme) && $theme === 'blue') ? 'stroke-white @@:h-[8px] w-auto -rotate-90' : 'stroke-dark-blue @@:h-[8px] w-auto -rotate-90', true); ?></span><span class="badge badge-primary badge-outlined"><?= $date_end ?></span><?php endif; ?>
  <?php if($types): ?>
    <span class="badge badge-primary badge-filled">
      <?= $types && $types[0] ? $types[0]->name : __('Event', 'atelierdesign'); ?>
    </span>      
  <?php endif; ?>

</div>
<?php $beforeContent = ob_get_clean(); ?>


<?php
ob_start();

if($themes): ?>
  <ul  class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-400 autoscale-children">
    <?php foreach($themes as $theme): ?>
      <li>
        <span class="badge badge-secondary badge-filled bg-yellow border-yellow"><?= $theme->name ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php $afterContent = ob_get_clean(); ?>

  <?php 
  echo get_template_part(
    '/components/hero/markup', 
    null, 
    [
      'title' => $title,
      'cover' => $cover,
      'content' => $description,
      'cover-status' => $coverState,
      'beforeContent' => $beforeContent,
      'afterContent' => $afterContent,
      'social'  => false,
      'size' => $size,
    ]);
?>