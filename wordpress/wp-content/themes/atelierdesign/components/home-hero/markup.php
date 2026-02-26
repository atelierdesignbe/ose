<?php
$title = $args['title'] ?? get_bloginfo('name');
$content = $args['content'];
$cover = $args['cover'];

// var_dump($title);
?>

<div class="hero hero-home bg-yellow-50 relative overflow-hidden ">
  <div class="hero-wrapper px-container z-[1] relative">
    <div class="grid grid-base relative @sm:gap-y-[42px] @md/lg:gap-y-[68px]">
      <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] col-span-12 md:col-span-18 md:col-start-4 autoscale aos animate-fadeinup"><?= $title ?></h1>
      <?php if($content): ?>
        <div class="md:absolute @md/lg:bottom-[-63px] md:translate-y-[100%] md:left-[58.33%]  col-span-12 @md/lg:max-w-[380px] @xl:max-w-[380px] ">
        <p class="autoscale aos animate-fadeinup animate-delay-200 paragraph paragraph-primary @md/lg:text-[18px] @@:pl-[28px] border-l border-dark-blue  @@:tracking-[1px] ">
          <?= $content ?>
        </p>
        </div>
      <?php endif;?>
    </div>
  </div>
  <div class="hero-media absolute inset-0 z-[0]">
    <div class="absolute inset-0 bg-yellow mix-blend-soft-light"></div>
    <div class="absolute inset-0 hero-gradient-home"></div>
    <div class="parallax-image-wrapper h-full relative aos animate-fadeinzoomout aniamte-delay-400">
      <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'parallax-image object-[20%_center] md:object-center object-cover w-full h-full']); ?>
    </div>
    <div class="absolute w-full h-[300px] top-0 left-0 bg-gradient-to-b from-yellow/80 from-40%  to-yellow/0 "></div>
  </div>
  <?php echo get_template_part('/components/scroll'); ?>
  <div class="hero-social theme-dark-blue bg-layout-main px-container absolute bottom-0 left-0 z-10 text-light-blue @md/lg:h-[96px] hidden md:flex items-center @@:gap-x-[42px]">
    <div class="aos animate-fadeinup flex-none">
      <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
    </div>
    <div class="bg-white w-full flex-auto h-[1px] opacity-20"></div>
  </div>
</div>