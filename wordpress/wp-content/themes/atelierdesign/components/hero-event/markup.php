<?php
$override = $args['hero-override'];
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$coverState = get_field('cover-status') ?? 'fill';

$date_start = get_field('date_start');
$date_end   = get_field('date_end');
$coverState = get_field('cover-status') ?? 'fill';

$ts_start = DateTime::createFromFormat('d-m-Y', $date_start)->getTimestamp();

if ($date_end) {
  $ts_end = DateTime::createFromFormat('d-m-Y', $date_end)->getTimestamp();
}

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );

if (!$cover) $coverState = 'none';
// var_dump($ts_start);
?>


<?php
ob_start();
?>
<div class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup">
  <?php if($date_start): ?> <span class="badge badge-primary badge-outlined"><?= $date_start ?></span><?php endif; ?>
  <?php if($date_end && $ts_end > $ts_start): ?><span><?= icon('chevron', $theme === 'blue' ? 'stroke-white @@:h-[8px] w-auto -rotate-90' : 'stroke-dark-blue @@:h-[8px] w-auto -rotate-90', true); ?></span><span class="badge badge-primary badge-outlined"><?= $date_end ?></span><?php endif; ?>
  <span class="badge badge-primary badge-filled bg-dark-blue text-white border-dark-blue">Event</span>
</div>
<?php $beforeContent = ob_get_clean(); ?>

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
      'social'  => false,
    ]);
?>