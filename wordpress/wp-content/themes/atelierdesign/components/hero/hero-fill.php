
<div class="hero hero-fill is-fullscreen">
  <div class="hero-grid">
    <div class="hero-fill-content">
      <?= $args['contentHTML']; ?>
    </div>
    <div class="hero-cover">
      <div class="absolute inset-0 hero-cover-wrap" >
        <div class="absolute inset-0 aos animate-fadeinzoomout" >
          <div class="absolute inset-0 parallax-image-wrapper">
            <?= wp_get_attachment_image($args['cover']['ID'], 'full', false, ['class' => 'parallax-image']) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
    
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute @sm:bottom-[400px] md:bottom-0 left-[50%] translate-x-[-60%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>

  <?php echo get_template_part('/components/scroll', 'scroll'); ?>
  <div class="absolute left-0  theme-white bg-layout-main px-container z-10 text-dark-blue hidden md:flex items-center @@:gap-x-[42px] @md/lg:h-[96px]" js-social>
    <div class="aos animate-fadeinup">
      <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
    </div>
  </div>
</div>
