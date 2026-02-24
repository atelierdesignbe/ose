<?php
$title = $args['title'] ?? get_the_title();
$content = $args['content'];
$cover = $args['cover'];
$label = get_the_title();
$labelStatus = $args['label-status'];
$coverState = $args['cover-status'];

$hasLabel = $labelStatus === 'override' || $labelStatus === 'default';
if ($hasLabel &&  $labelStatus === 'override') $label = $args['label'];
if (!$cover) $coverState = 'none';
?>
<div class="hero hero-cpt relative overflow-hidden relative <?= $coverState === 'fill' ? 'mm-sm:pb-0' : '' ?> ">
  <div class="px-container relative z-10 w-full">
    <div class="grid grid-base">
      <div class="hero-wrapper z-[1] relative col-span-12  @md:py-[130px] @lg:py-[130px] flex flex-col @@:gap-y-[16px] autoscale-children <?= $coverState === 'default' ? 'theme-dark-blue md:col-span-18 md:col-start-4 items-center text-center' : '' ?>">
        <div class="flex items-center @@:gap-x-2 aos animate-fadeinup">
          <?php if($label && $hasLabel): ?><span class="subtitle paragraph-primary aos animate-fadeinup"><?= $label ?></span><?php endif; ?>
        </div>
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup autoscale"><?= $title ?></h1>
        <?php if($content): ?><p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200 autoscale"><?= $content ?></p><?php endif; ?>
      </div>
    </div>
  </div>
  <?php if($coverState === 'none'):  ?>
    <div class="absolute bottom-0 left-[--left-line] w-[1px] h-full bg-dark-blue opacity-20 z-[0] md:h-[--hero-h] mm-sm:hidden"></div>
  <?php elseif($coverState === 'fit'):  ?>
    <div class="hero-cover hero-cover--fit mm-sm:px-container z-[1] mm-sm:w-full md:absolute md:bottom-0 md:right-0 md:h-[--hero-h]">
      <div class="absolute top-0 left-[22.22%] w-[1px] h-full bg-dark-blue opacity-20 z-[0] mm-sm:hidden"></div>
      <div class="w-full h-full z-[1] relative flex items-center mm-sm:justify-center">
        <?php 
          echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'h-auto w-full md:w-auto md:h-full @md/lg:max-h-[400px] w-auto image-shadow-lg aos animate-fadeinup animate-delay-400']);
        ?> 
      </div>
    </div>
  <?php elseif($coverState === 'fill'): ?>
    <div class="hero-cover hero-cover--fill z-[1] @sm:h-[400px] mm-sm:w-full md:absolute md:bottom-0 md:right-0 md:h-[--hero-h] overflow-hidden">
      <div class="w-full h-full z-[1] relative parallax-image-wrapper aos animate-fadeinzoomout animate-delay-400 ">
        <?php 
          echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'w-full h-full object-cover parallax-image']);
        ?> 
      </div>
    </div>
  <?php elseif($coverState === 'default'): ?>
    <div class="z-[0] absolute w-full h-full top-0 left-0 overflow-hidden">
      <div class="w-full h-full z-[1] relative parallax-image-wrapper aos animate-fadeinzoomout animate-delay-400 ">
        <!-- HERE BKG -->
        <?php 
          echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'w-full h-full object-cover parallax-image']);
        ?> 
        <div class="absolute inset-0 bg-layout-main theme-dark-blue opacity-20"></div>
        <div class="absolute inset-0 gradient-fullsize mix-blend-darken"></div>
      </div>
    </div>
  <?php endif; ?>

  <?php if($coverState === 'fit' || $coverState === 'none'): ?>
    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>
  <?php endif;?>

  <?php if($coverState === 'fill') : ?>
    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute @sm:bottom-[400px] md:bottom-0 left-[50%] translate-x-[-60%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>
  <?php endif;?>

  <?php echo get_template_part('/components/scroll', 'scroll',  ['isFit' => $coverState === 'fit']); ?>
  <div class=" theme-white bg-layout-main px-container absolute bottom-0 left-0 z-10 text-dark-blue @md/lg:h-[96px] hidden md:flex items-center @@:gap-x-[42px]">
    <div class="aos animate-fadeinup flex-none">
      <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
    </div>
  </div>
</div>