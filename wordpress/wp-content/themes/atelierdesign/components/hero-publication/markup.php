<?php
$override = $args['hero-override'];
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$category = get_field('category');
$authors = get_field('author');

$hasDate = true;
$hasDescription = true;
$coverState = 'fit';

if ($override):
  $title = $args['title'] && !empty($title) ? $args['title'] : $title;
  $hasDate = $args['hero-date'];
  $hasDescription = $args['hero-description'];
  $coverState = $args['cover-status'] ? 'fit' : 'none';
endif;

$wrapperClass = ' @sm:pt-[144px] @sm:pb-[40px]';
if ($coverState === 'none') $wrapperClass = ' @sm:pt-[144px] @sm:pb-[80px]';

$heroClass = 'absolute inset-0 ';
if ($coverState === 'fit') $heroClass = 'absolute bottom-0 right-0 md:h-[--hero-h] @md/lg:pr-[--pl-margin] @md/lg:py-[--pl-margin]';

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projects = get_the_terms( get_the_ID(), 'projects' );

?>
<div class="hero hero-page md:h-screen @md/lg:max-h-[720px] relative overflow-hidden flex md:items-center relative hero-fill">
  <div class="px-container relative z-10 w-full">
    <div class="grid grid-base">
      <div class="hero-wrapper z-[1] relative col-span-12  @md/lg:py-[130px] flex flex-col @@:gap-y-[16px] <?= $wrapperClass ?>">
        <div class="flex items-center @@:gap-x-2 autoscale-children aos animate-fadeinup">
          <?php if($hasDate): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
          <span class="badge badge-primary badge-filled bg-dark-blue border-dark-blue text-white"><?= $category ? 'In depth' : 'Sumary publication' ?></span>
        </div>
        <?php if($projects && sizeof($projects) > 0): ?>
          <ul class="flex items-center @@:gap-[8px] autoscale-children aos animate-fadeinup">
            <?php foreach($projects as $project):?>
              <li>
                <a href="#" class="button button-primary button-underline"><span class="button-title"><?= $project->name ?></span></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup autoscale"><?= $title ?></h1>
        <?php if($hasDescription): ?><p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200 autoscale"><?= $description ?></p><?php endif; ?>
        <?php if($authors): ?>
          <ul class="flex flex-wrap items-center @@:gap-[8px] autoscale-children aos animate-fadeinup animate-delay-300">
            <?php foreach($authors as $author):?>
              <li>
                <span class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]"><?= $author->post_title; ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($types): ?>
          <ul  class="flex items-center flex-wrap @@:gap-x-2 aos animate-fadeinup animate-delay-400 autoscale-children">
            <?php foreach($types as $type): ?>
              <li class="badge badge-secondary badge-filled "><?= $type->name ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($themes): ?>
          <ul  class="flex items-center flex-wrap @@:gap-x-2 aos animate-fadeinup animate-delay-500 autoscale-children">
            <?php foreach($themes as $theme): ?>
              <li class="badge badge-primary badge-filled bg-yellow border-yellow"><?= $theme->name ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
     
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
  <?php endif; ?>

  <?php if($coverState === 'none'): ?>
    <div class="absolute bottom-0 left-[62.5%] w-[1px] h-full bg-dark-blue opacity-20 z-[0] md:h-[--hero-h] mm-sm:hidden"></div>
    <?php echo get_template_part('/components/gradient', null, ['class' => 'absolute bottom-0 left-0 z-[0] scale-y-[-1] mix-blend-color']); ?>
    <!-- <div class="absolute gradient-hero-fill"></div> -->
  <?php endif; ?>


  <?php echo get_template_part('/components/scroll'); ?>
</div>