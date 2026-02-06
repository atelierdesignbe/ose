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



$coverPosition = 'object-cover';
if ($coverState === 'fit') $coverPosition = 'object-fit @md/lg:py-[--pl-margin] @md/lg:pr-[--pl-margin]';
?>
<!--  -->
<div class="hero hero-cpt md:h-screen @md/lg:max-h-[720px] bg-yellow-50 relative overflow-hidden flex md:items-center ">
  <div class="px-container">
    <div class="grid grid-base">
      <div class="hero-wrapper z-[1] relative col-span-12 md:col-span-13 @md/lg:py-[130px] @@:gap-y-[16px] flex flex-col">
        <div class="flex flex-row @@:gap-x-2 items-start autoscale-children">
          <?php if($dateStart && $hasDate): ?><span class="badge bdage-primary badge-outlined"><?= $dateStart ?></span><?php endif; ?>
          <?php if($dateEnd): ?><span class="badge bdage-primary badge-outlined"><?= $dateEnd ?></span><?php endif; ?>
          <span class="badge badge-primary badge-filled bg-dark-blue text-white border-none">Event</span>
        </div>
        <!-- DATE  -->
        <!--  -->
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup autoscale"><?= $title ?></h1>
        <p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200 autoscale"><?= $description ?></p>
      </div>
      <div class="hero-cover absolute bottom-0 right-0 md:h-[--hero-h] hero-<?= $coverState ?>">
        <?php if($coverState === 'fill'): ?>
          <div class="parallax-image-wrapper w-full h-full ">
            <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'parallax-image w-full h-full object-cover' ]); ?>
          </div>
        <?php elseif($coverState === 'fit'): ?>
          <div class="w-full h-full @md/lg:pr-[--pl-margin] @md/lg:py-[--pl-margin]">
            <!-- <div class="h"> -->
              <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => ' w-full h-full object-contain' ]); ?>
            <!-- </div> -->
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>