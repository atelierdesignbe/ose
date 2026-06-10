<?php
$isFullscreen = $args['size'] === 'fullscreen';

?>
<div class="hero hero-fit <?= $isFullscreen ? 'is-fullscreen' : '' ?> <?= $args['social'] ? 'has-social' : ''?>">
  <div class="container relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-24">
      <div class="hero-fit-content md:col-span-13">
        <?= $args['contentHTML']; ?>
      </div>
      <div class="hero-cover md:col-span-9 md:col-start-16 z-[99]">
        <div class="hero-cover-wrap ">
          <?= wp_get_attachment_image($args['cover']['ID'], 'full', false, ['class' => 'aos animate-fadeinup image-shadow-lg']) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="absolute bottom-0 left-[--left-line] w-[1px] md:h-[--hero-h] bg-dark-blue opacity-20 z-[2] mm-sm:hidden"></div>

  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>
  

  <?php echo get_template_part('/components/scroll', 'scroll'); ?>
  <?php if($args['social']): ?>
    <div class="absolute left-0  theme-white bg-layout-main px-container z-10 text-dark-blue hidden md:flex items-center @@:gap-x-[42px] @md:h-[96px] @lg:h-[96px] @xl:h-[96px]" js-social>
      <div class="aos animate-fadeinup">
        <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
      </div>
    </div>
  <?php endif; ?>
</div>

