<?php

/**
 * Template Name: Projects
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php
  $fields = get_fields();

  $args = array(
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => 18,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'DESC',
  );

  $projects = new WP_Query($args);

  $args = array(
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => -1,
  );

  $allProjects = new WP_Query($args);
  $years = [];

  foreach($allProjects->posts as $post): 
    $date = get_field('date_start', $post->ID);
    $year = explode('-', $date)[2];
    if (!in_array($year, $years))$years[] = $year;
  endforeach;

  $types = get_terms([
    'taxonomy'   => 'types', // Remplace 'type' par le nom exact de ta taxonomie
    'hide_empty' => true,
  ]);

  $themes = get_terms([
    'taxonomy'   => 'themes', // Remplace 'type' par le nom exact de ta taxonomie
    'hide_empty' => true,
  ]);

?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="projects" js-ajax="project">
  <div class="px-container @sm:pt-[120px] @md/lg:pt-[144px] @@:pb-[78px]">
    <div class="w-full @md/lg:max-w-[945px] ">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadeinup"><?= get_the_title(); ?></h1>
        <?php if($fields['content']): ?><p class="paragraph paragraph-xl paragraph-primary text-balance aos animate-fadeinup animate-delay-200"><?= $fields['content'] ?></p><?php endif; ?>
      </div>
    </div>

    <!-- FILTER -->
    <div class="filter flex flex-col md:flex-row md:items-center flex-wrap @@:gap-[16px] @@:mt-[60px] aos animate-fadeinup">
      <p class="filter-label subtitle autoscale text-dark-blue">Filter by</p>
      <!-- <div class=""></div> -->
      <div class="flex flex-wrap flex-row mm-sm:justify-between @sm:gap-y-[12px] @md/lg:gap-x-4">
        <?php if($themes): ?>
          <div class="relative mm-sm:w-[48%] md:w-auto">
            <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between" js-expand-button>
              <span class="button-title">Theme</span>
              <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
            </button>
            <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 left-0 translate-y-full z-[10] mm-sm:w-[--size-container] md:w-full" js-expand="all">
              <ul class="border border-yellow @@:px-[20px] @@:py-[12px] @md/lg:min-w-[160px] w-full bg-white text-dark-blue" js-ajax-filter="themes">
                <?php foreach($themes as $theme): ?>
                  <li js-expand-item>
                    <button type="button" class="button-title" data-id="<?= $theme->term_id ?>" data-name="<?= $theme->name ?>">
                      <span><?= $theme->name ?></span>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        <!-- PERIOD -->
        <?php if($years): ?>
          <div class="relative mm-sm:w-[48%]">
            <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between" js-expand-button>
              <span class="button-title">Period</span>
              <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
            </button>
            <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 right-0 translate-y-full z-[10] mm-sm:w-[--size-container] md:w-full" js-expand="all">
              <ul class="border border-yellow @@:px-[20px] @@:py-[12px] @md/lg:min-w-[160px] bg-white text-dark-blue w-full" js-ajax-filter="period">
                <?php foreach($years as $year): ?>
                  <li js-expand-item>
                    <button data-id="<?= $year ?>" data-name="<?= $year ?>" type="button" class="button-title">
                      <span><?= $year ?></span>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if($types): ?>
          <div class="relative mm-sm:w-[48%]">
            <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between" js-expand-button>
              <span class="button-title">Type</span>
              <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
            </button>
            <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 left-0 translate-y-full z-[10] mm-sm:w-[--size-container] md:w-full" js-expand="all">
              <ul class="border border-yellow @@:px-[20px] @@:py-[12px] @md/lg:min-w-[160px] bg-white text-dark-blue w-full" js-ajax-filter="types">
                <?php foreach($types as $type): ?>
                  <li js-expand-item>
                    <button type="button" class="button-title" data-id="<?= $type->term_id ?>" data-name="<?= $type->name ?>" >
                      <span><?= $type->name ?></span>
                    </button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="filter-reset autoscale-children flex items-center flex-wrap @@:gap-2 relative z-[1]" js-ajax-reset></div>
    </div>

    <?php if ( $projects->have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[48px] *:md:stagger-3" js-ajax-results>
        <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
          <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
            <?php echo get_template_part('/components/project', null, array('id' => get_the_ID())); ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
    <!-- $query->found_posts > $query->post_count -->
    <div class="flex items-center justify-center" js-ajax-pagination>
      <?php if($projects->found_posts > $projects->post_count): ?>
        <button type="button" class="button button-primary button-flat autoscale @@:mt-[60px]">
          <span class="button-title">Load more</span>
        </button>
      <?php endif; ?>
    </div>
  </div>
  <?php  wp_reset_postdata(); // ← Important ! ?>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>