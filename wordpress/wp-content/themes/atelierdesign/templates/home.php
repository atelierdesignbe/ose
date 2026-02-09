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
    <div class="flex mm-sm:flex-wrap items-start">
      <div class="w-full md:w-[--col-1-3] bg-layout-main theme-dark-blue @sm:py-[60px] @md/lg:py-[80px] px-container flex flex-col @sm:gap-y-[34px] @md/lg:gap-y-[50px] items-start autoscale-children flex-none md:sticky md:top-0">
        <?php if($introTitle): ?><h2 class="heading-2xl heading-primary heading aos animate-fadeinup"><?= $introTitle ?></h2><?php endif; ?>
        <?php if($introLink): ?>
          <a href="<?= $introLink['url']; ?>" class="button button-flat button-primary aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $introLink['title']; ?></span>
          </a>
        <?php endif; ?>
      </div>
      <div class="w-full md:flex-auto">
        <div class="@sm:py-[60px] @md/lg:py-[80px] px-container">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $introContent, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="bg-layout-main theme-light-grey px-container sm:py-[60px] @md/lg:py-[80px] grid grid-cols-1 md:grid-cols-2 @md/lg:gap-x-[60px]">
          <?php foreach($areas as $i => $item): ?>
            <div class="col-span-1 flex flex-col @sm:gap-y-4 @md/lg:gap-y-[28px] text-dark-blue border-[--dark-blue-20] border-b @sm:pb-[42px] @sm:mb-[42px] @md/lg:pb-[58px] @md/lg:mb-[58px] <?php if(($i + 2) >= sizeof($areas)) { echo " md:mb-0 md:border-b-0";} ?> ">
              <span class="subtitle aos animate-fadeinup"><?= $i < 8 ? '0'.($i + 1) : $i + 1 ?></span>
              <?php $adwp->get_template_part('_wysiwyg',  array('content' => $item['content'], 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php get_template_part('/components/publications/markup', 'publications', $fields['publications']); ?>
  <?php get_template_part('/components/grid/markup', 'grid', $fields['grid']); ?>
  <?php get_template_part('/components/events/markup', 'events', []); ?>
  <?php get_template_part('/components/projects/markup', 'projects',  $fields['projects']); ?>

  <!-- LAST  -->
  <?php 
    $insightContent = $fields['insights-content'];
    $insights = $fields['insights'];
  ?>
  <div class="py-section theme-white bg-layout-main">
    <div class="px-container">
      <div class="grid grid-cols-1 md:grid-cols-3 @md/lg:gap-x-[60px]">
        <div class="col-span-1 text-balance">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $insightContent, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="col-span-1 md:col-span-2">
            <div class="flex flex-col @@:gap-y-[32px]">
              <?php foreach($insights as $item): ?>
                <div class="flex flex-col md:flex-row items-center aos animate-fadein">
                  <div class="w-full @sm:h-[260px] @md/lg:w-[248px] @md/lg:h-[268px]  flex-none">
                    <?php echo wp_get_attachment_image($item['cover']['ID'], "full", null, ["class" => 'object-cover  h-full w-full']); ?>
                  </div>
                  <div class="flex-auto flex flex-col w-full @@:gap-y-4 items-start @@:py-[30px] @md/lg:px-[20px] autoscale-children">
                    <?php if($item['date']): ?>
                      <span class="badge badge-primary badge-outlined"><?= $item['date'] ?></span>
                    <?php endif; ?>
                    <?php if($item['content']): ?>
                      <p class="paragraph paragraph-md paragraph-primary"><?= $item['content'] ?></p>
                    <?php endif; ?>
                    <?php if($item['link']): ?>
                      <a href="<?= $item['link']['url']; ?>" class="button button-underline button-primary"> <span class="button-title"><?= $item['link']['title'] ?></span> </a>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
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