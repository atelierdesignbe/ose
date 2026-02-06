<?php
$title = $args['title'] ?? get_bloginfo('name');
$content = $args['content'];
$cover = $args['cover'];

// var_dump($title);
?>

<div class="hero md:h-screen @md/lg:max-h-[720px] bg-yellow-50 relative overflow-hidden flex items-end @sm:pt-[148px] @sm:pb-[80px] @md/lg:pb-[60px] @md/lg:pt-[220px]">
  <div class="hero-wrapper px-container z-[1] relative">
    <div class="flex flex-col @sm:gap-y-[42px] @md/lg:gap-y-[68px]">
      <div class="grid-base">
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] col-span-12 md:col-span-18 md:col-start-4 autoscale aos animate-fadeinup"><?= $title ?></h1>
      </div>
      <div class="grid-base">
        <?php if($content): ?>
          <p class="paragraph paragraph-primary @md/lg:text-[18px] @@:pl-[28px] border-l border-dark-blue col-span-12 md:col-start-16 md:col-span-7 autoscale aos animate-fadeinup animate-delay-200 @@:tracking-[1px]"><?= $content ?></p>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="hero-media absolute inset-0 z-[0]">
    <div class="parallax-image-wrapper h-full">
      <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'parallax-image object-cover w-full h-full']); ?>
    </div>
  </div>
  <?php echo get_template_part('/components/scroll'); ?>
  <div class="social theme-dark-blue bg-layout-main px-container absolute bottom-0 left-0 z-10 text-light-blue @md/lg:h-[96px] flex items-center mm-sm:hidden">
    <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
  </div>
</div>