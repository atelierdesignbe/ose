<?php

/**
 * Template Name: Home
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="home">
  <?php 
  $fields = get_fields();

  /** */
  ?>
  <?php get_template_part('/components/home-hero/markup', 'home-hero', $fields['hero']); ?>
  <?php
    $introTitle = $fields['intro-title'];
    $introContent = $fields['intro-content'];
    $introLink = $fields['intro-link'];
    $areas = $fields['intro-areas'];
  ?>
  <div class="home-intro">
    <div class="flex items-start">
      <div class="w-full md:w-[--col-1-3] bg-layout-main theme-dark-blue @sm:py-[60px] @md/lg:py-[80px] px-container flex flex-col @sm:gap-y-[34px] @md/lg:gap-y-[50px] items-start autoscale-children flex-none md:sticky md:top-0">
        <?php if($introTitle): ?><h2 class="heading-2xl heading-primary heading aos animate-fadeinup"><?= $introTitle ?></h2><?php endif; ?>
        <?php if($introLink): ?>
          <a href="<?= $introLink['url']; ?>" class="button button-flat button-primary aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $introLink['title']; ?></span>
          </a>
        <?php endif; ?>
      </div>
      <div class="md:flex-auto">
        <div class="@sm:py-[60px] @md/lg:py-[80px] px-container">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $introContent, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="bg-layout-main theme-light-grey px-container sm:py-[60px] @md/lg:py-[80px] grid grid-cols-1 md:grid-cols-2 @md/lg:gap-x-[60px]">
          <?php foreach($areas as $i => $item): ?>
            <div class="col-span-1 flex flex-col @sm:gap-y-4 @md/lg:gap-y-[28px] text-dark-blue border-[--dark-blue-20] border-b @md/lg:pb-[58px] @md/lg:mb-[58px] <?php if(($i + 2) >= sizeof($areas)) { echo " md:mb-0 md:border-b-0";} ?> ">
              <span class="subtitle aos animate-fadeinup"><?= $i < 8 ? '0'.($i + 1) : $i + 1 ?></span>
              <?php $adwp->get_template_part('_wysiwyg',  array('content' => $item['content'], 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- <article class="article">
  </article> -->
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>