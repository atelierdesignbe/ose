<?php
$override = $args['hero-override'];
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$dateStart = get_field('date_start');
$dateEnd = get_field('date_end');

$hasDate = true;
$hasDescription = true;
$coverState = 'fill';

if ($override):
  $title = $args['title'] && !empty($title) ? $args['title'] : $title;
  $hasDate = $args['hero-date'];
  $hasDescription = $args['hero-description'];
  $coverState = $args['cover-status'];
endif;

$wrapperClass = ' items-center justify-center text-center md:col-span-16 md:col-start-5 theme-dark-blue @sm:pt-[144px] @sm:pb-[80px]';
if ($coverState === 'fit') $wrapperClass = ' @sm:pt-[144px] @sm:pb-[40px]';
if ($coverState === 'fill') $wrapperClass = ' @sm:pt-[144px] @sm:pb-[80px]';
if ($coverState === 'none') $wrapperClass = ' @sm:pt-[144px] @sm:pb-[80px]';

$heroClass = 'absolute inset-0 ';
if ($coverState === 'fill') $heroClass = 'absolute bottom-0 right-0 md:h-[--hero-h]';
else if ($coverState === 'fit') $heroClass = 'absolute bottom-0 right-0 md:h-[--hero-h] @md/lg:pr-[--pl-margin] @md/lg:py-[--pl-margin]';
?>
<div class="hero hero-page md:h-screen @md/lg:max-h-[720px] relative overflow-hidden flex md:items-center relative hero-fill">
  <div class="px-container relative z-10 w-full">
    <div class="grid grid-base">
      <div class="hero-wrapper z-[1] relative col-span-12  @md/lg:py-[130px] flex flex-col @@:gap-y-[16px] <?= $wrapperClass ?>">
        <div class="flex items-center @@:gap-x-2 autoscale-children aos animate-fadeinup">
          <?php if($dateStart): ?> <span class="badge badge-primary badge-outlined"><?= $dateStart ?></span><?php endif; ?>
          <?php if($dateEnd && $dateEnd > $dateStart): ?><span><?= icon('chevron', 'stroke-dark-blue @@:h-[8px] w-auto -rotate-90'); ?></span> <span class="badge badge-primary badge-outlined"><?= $dateEnd ?></span><?php endif; ?>
          <span class="badge badge-primary badge-filled">Event</span>
        </div>
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup autoscale"><?= $title ?></h1>
        <?php if($hasDescription): ?><p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200 autoscale"><?= $description ?></p><?php endif; ?>
      </div>
    </div>
  </div>

  <?php if($coverState === 'fit'): ?>
    <div class="hero-cover mm-sm:px-container z-[1] mm-sm:w-full md:absolute md:bottom-0 md:right-0 md:h-[--hero-h] @md/lg:pr-[--pl-margin] @md/lg:py-[--pl-margin] @sm:pb-[80px] flex items-center md:pt-0">
      <div class="absolute top-0 left-[22.22%] w-[1px] h-full bg-dark-blue opacity-20 z-[0]"></div>
      <div class="w-full h-full @md/lg:max-h-[320px] z-[1] relative flex items-center">
        <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'w-auto h-auto max-w-[100%] max-h-[100%] image-shadow aos animate-fadeinup animate-delay-400']); ?>
      </div>
    </div>
    <?php echo get_template_part('/components/gradient', null, ['class' => 'absolute bottom-0 left-0 z-[0] scale-y-[-1] mix-blend-color']); ?>
    <?php echo get_template_part('/components/gradient', null, ['class' => 'absolute top-0 right-0 z-[0] scale-x-[-1] mix-blend-color hidden lg:block']); ?>
  <?php elseif($coverState === 'fill'): ?>
    <div class="hero-cover z-[0] md:absolute md:bottom-0 md:right-0 md:h-[--hero-h]">
      <div class="w-full h-full parallax-image-wrapper">
        <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'object-cover w-full h-full parallax-image']); ?>
      </div>
    </div>
    <?php echo get_template_part('/components/gradient', null, ['class' => 'absolute bottom-0 left-0 z-[0] scale-y-[-1] mix-blend-color']); ?>
  <?php endif; ?>

  <?php if($coverState === 'none'): ?>
    <div class="absolute bottom-0 left-[62.5%] w-[1px] h-full bg-dark-blue opacity-20 z-[0] md:h-[--hero-h] mm-sm:hidden"></div>
    <?php echo get_template_part('/components/gradient', null, ['class' => 'absolute bottom-0 left-0 z-[0] scale-y-[-1] mix-blend-color']); ?>
    <!-- <div class="absolute gradient-hero-fill"></div> -->
  <?php endif; ?>
    
  <?php echo get_template_part('/components/scroll'); ?>
</div>