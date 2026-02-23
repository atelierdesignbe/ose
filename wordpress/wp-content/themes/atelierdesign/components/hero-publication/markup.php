<?php
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$category = get_field('category');
$authors = get_field('author');
if(!$cover) $cover = get_field('publication-placeholder', 'acf-options-global-fields') ;

// $wrapperClass = ' @sm:pt-[144px] @sm:pb-[40px]';
// if ($coverState === 'none') $wrapperClass = ' @sm:pt-[144px] @sm:pb-[80px]';

// $heroClass = 'absolute inset-0 ';
// if ($coverState === 'fit') $heroClass = 'absolute bottom-0 right-0 md:h-[--hero-h] @md/lg:pr-[--pl-margin] @md/lg:py-[--pl-margin]';

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projects = get_the_terms( get_the_ID(), 'projects' );

?>
<div class="hero hero-cpt relative overflow-hidden relative ">
  <div class="px-container relative z-10 w-full">
    <div class="grid grid-base">
      <div class="hero-wrapper z-[1] relative col-span-12  flex flex-col @@:gap-y-[16px]">
        <div class="flex items-center @@:gap-x-2 autoscale-children aos animate-fadeinup">
          <?php if($date): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
            
          <span class="badge badge-primary badge-filled bg-dark-blue border-dark-blue text-white"><?= $category ? 'In depth' : 'Sumary publication' ?></span>
        </div>
        <?php if($projects && sizeof($projects) > 0): ?>
          <ul class="flex items-center @@:gap-[8px] autoscale-children aos animate-fadeinup">
            <?php foreach($projects as $project):?>
              <li>
                <a href="/publications/?projects=<?= $project->term_id ?>" class="button button-primary button-underline"><span class="button-title"><?= $project->name ?></span></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup autoscale"><?= $title ?></h1>
        <?php if($description): ?><p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200 autoscale"><?= $description ?></p><?php endif; ?>
        <?php if($authors): ?>
          <ul class="flex flex-wrap items-center @@:gap-[8px] autoscale-children aos animate-fadeinup animate-delay-300">
            <?php foreach($authors as $author):?>
              <li>
                <a href="/publications/?authors=<?= $author->ID ?>" class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]"><?= $author->post_title; ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($types): ?>
          <ul  class="flex items-center flex-wrap @@:gap-x-2 aos animate-fadeinup animate-delay-400 autoscale-children">
            <?php foreach($types as $type): ?>
              <a href="/publications/?type=<?= $type->term_id ?>" class="badge badge-primary badge-filled">
                <?= $type->name ?>
              </a>            
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($themes): ?>
          <ul  class="flex items-center flex-wrap @@:gap-x-2 aos animate-fadeinup animate-delay-500 autoscale-children">
            <?php foreach($themes as $theme): ?>
              <li >
                <a href="/publications/?theme=<?= $theme->term_id ?>" class="badge badge-primary badge-filled bg-yellow border-yellow"><?= $theme->name ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
     
      </div>
    </div>
  </div>

  <div class="hero-cover hero-cover--fit mm-sm:px-container z-[1] mm-sm:w-full md:absolute md:bottom-0 md:right-0 md:h-[--hero-h]">
    <div class="absolute top-0 left-[22.22%] w-[1px] h-full bg-dark-blue opacity-20 z-[0] mm-sm:hidden"></div>
    <div class="w-full h-full z-[1] relative flex items-center mm-sm:justify-center">
      <?php 
        echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'h-auto @sm:w-[83.33%] md:max-h-full md:w-auto w-auto image-shadow-lg aos animate-fadeinup animate-delay-400']);
      ?> 
    </div>
  </div>
  
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>
  <?php echo get_template_part('/components/scroll', 'scroll', ['isFit' => true]); ?>
</div>