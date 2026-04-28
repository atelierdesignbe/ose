<?php
// BASE ----
$title = get_the_title();
$cover = get_field('cover');
$content = get_field('description');
$date_start = get_field('date_start');
$date_end = get_field('date_end');
$coverState = get_field('cover-status') ?? 'fill';
if(!$cover) $coverState = 'none';

$theme = 'theme-white';
$context = $args['context'];

$coverClass = array(
  'wrap' => 'parallax-image-wrapper aos animate-fadeinzoomout',
  'img' => 'parallax-image',
);

if ($coverState === 'fit') {
  $coverClass = array(
    'wrap' => '',
    'img' => 'image-shadow-lg aos animate-fadeinup animate-delay-400',
  );
  
}
?>


<div class="hero hero-<?= $coverState ?> <?= $theme ?> hero-cpt">
  <div class="hero-wrapper">
    <div class="relative z-10 px-container">
      <div class="hero-content">

        <div class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup">
          <?php if($date_start): ?> <span class="badge badge-primary badge-outlined"><?= $date_start ?></span><?php endif; ?>
          <?php if($date_end && $ts_end > $ts_start): ?><span><?= icon('chevron', $theme === 'blue' ? 'stroke-white @@:h-[8px] w-auto -rotate-90' : 'stroke-dark-blue @@:h-[8px] w-auto -rotate-90'); ?></span><span class="badge badge-primary badge-outlined"><?= $date_end ?></span><?php endif; ?>
          <span class="badge badge-primary badge-filled bg-dark-blue text-white border-dark-blue">Event</span>
        </div>

        <?php if ($title): ?>
          <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup">
            <?= esc_html($title) ?>
          </h1>
        <?php endif; ?>

        <?php if ($content): ?>
          <p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200">
            <?= wp_kses_post($content) ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
    <!-- COVER -->
    <?php if($coverState !== 'none'): ?>
      <div class="hero-cover">
        <div class="hero-cover-wrap <?= $coverClass['wrap'] ?>">
          <?php if($coverState === 'fit'): ?>
            <div class="hero-cover-fit">
              <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => $coverClass['img']]); ?> 
            </div>
          <?php else:  ?>
            <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => $coverClass['img']]); ?> 
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
  
  <?php echo get_template_part('/components/scroll', 'scroll'); ?>

  <?= $context ?>
</div>