
<?php
global $adwp;
?>

<div class="hero hero-fullsize is-fullscreen ">
  <div class="hero-fullsize-wrapper theme-dark-blue">
    <div class="px-container relative w-full">
      <?= $args['contentHTML']; ?>
    </div>
    <div class="hero-cover">
      <div class="absolute inset-0 aos animate-fadeinzoomout">
        <div class="absolute inset-0 parallax-image-wrapper">
          <?= wp_get_attachment_image($args['cover']['ID'], 'full', false, ['class' => 'parallax-image']) ?>
        </div>
      </div>
    </div>
  </div>
  <?php echo get_template_part('/components/scroll', 'scroll'); ?>
  <div class="absolute left-0  theme-white bg-layout-main px-container z-10 text-dark-blue hidden md:flex items-center @@:gap-x-[42px] @md/lg:h-[96px]" js-social>
    <div class="aos animate-fadeinup">
      <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
    </div>
  </div>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>
  <div class="absolute inset-0 hero-gradient-header z-[8]"></div>
  <div class="absolute inset-0 bg-layout-main theme-dark-blue opacity-20"></div>
  <div class="absolute inset-0 gradient-fullsize mix-blend-darken"></div>
</div> 