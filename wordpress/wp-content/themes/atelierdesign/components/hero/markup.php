<?php
// BASE ----
$title      = $args['title'] ?? get_the_title();
$content    = $args['content'] ?? null;
$cover      = $args['cover'] ?? null;
$label      = $args['label-status'] === 'override' ? ($args['label'] ?? null) : get_the_title();
$labelStatus = $args['label-status'] ?? 'default';
$coverState  = $cover ? ($args['cover-status'] ?? 'none') : 'none';
$theme = $coverState === 'default' ? 'theme-dark-blue' : 'theme-white';
$context = $args['context'];
$hasScroll = $args['hasScroll'] ?? true;
$hasSocial = $args['hasSocial'] ?? true;

$coverClass = array(
  'wrap' => 'parallax-image-wrapper aos animate-fadeinzoomout',
  'img' => 'parallax-image',
);

?>
<div class="hero hero-<?= $coverState ?> <?= $theme ?>">
  <div class="hero-wrapper">
    <div class="relative z-10 px-container">
      <div class="hero-content">
        <?php if ($label && $labelStatus !== 'disabled'): ?>
          <div class="flex items-center @@:gap-x-2 aos animate-fadeinup">
            <span class="subtitle paragraph-primary"><?= esc_html($label) ?></span>
          </div>
        <?php endif; ?>

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
    <div class="hero-cover">
      <div class="hero-cover-wrap <?= $coverClass['wrap'] ?>">
        <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => $coverClass['img']]); ?> 
      </div>
    </div>
  </div>
  
  <?php if($hasScroll): ?>
    <?php echo get_template_part('/components/scroll', 'scroll'); ?>
  <?php endif; ?>

  <?= $context ?>

</div>