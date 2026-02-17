<?php

/**
 * Template Name: Events
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php
  $fields = get_fields();

  $args = array(
    'post_type' => 'event',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'date_start',
        'value' => date('Ymd'), // Date d'aujourd'hui au format YYYYMMDD
        'compare' => '>=',      // Supérieur ou égal à aujourd'hui
        'type' => 'NUMERIC',
      ),
    ),
  );
    
  $events = new WP_Query($args);

?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="publications">
  <div class="px-container @sm:pt-[120px] @md/lg:pt-[144px] @@:pb-[78px]">
    <div class="w-full @md/lg:max-w-[945px] ">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadeinup"><?= get_the_title(); ?></h1>
        <?php if($fields['content']): ?><p class="paragraph paragraph-xl paragraph-primary text-balance aos animate-fadeinup animate-delay-200"><?= $fields['content'] ?></p><?php endif; ?>
      </div>
    </div>
    <?php if ( $events->have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[54px] @@:mt-[48px] *:md:stagger-3">
        <?php while ( $events->have_posts() ) : $events->the_post(); ?>
          <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
            <?php echo get_template_part('/components/event', null, array('id' => get_the_ID())); ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php  wp_reset_postdata(); // ← Important ! ?>
  <?php
    /** PAST EVENT */
    $args = array(
      'post_type' => 'event',
      'post_status' => 'publish',
      'posts_per_page' => 18,
      'meta_key' => 'date_start',
      'orderby' => 'meta_value',
      'order' => 'DESC',
      'meta_query' => array(
        array(
          'key' => 'date_start',
          'value' => date('Ymd'), // Date d'aujourd'hui au format YYYYMMDD
          'compare' => '<',      // Supérieur ou égal à aujourd'hui
          'type' => 'NUMERIC',
        ),
      ),
    );
    
  $oldevents = new WP_Query($args);
  ?>
  <?php if($oldevents): ?>
    <div class="theme-dark-blue bg-layout-main py-section">
      <div class="px-container">
        <h2 class="heading heading-2xl heading-primary aos animate-fadeinup">Past events</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[54px] @@:mt-[48px] *:md:stagger-3">
          <?php while ( $oldevents->have_posts() ) : $oldevents->the_post(); ?>
            <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
              <?php echo get_template_part('/components/event', null, array('id' => get_the_ID(), 'theme' => 'blue')); ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php  wp_reset_postdata(); // ← Important ! ?>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>