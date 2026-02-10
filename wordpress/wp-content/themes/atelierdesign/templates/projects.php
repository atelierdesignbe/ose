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
?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="projects">
  <div class="px-container @sm:pt-[120px] @md/lg:pt-[144px] @@:pb-[78px]">
    <div class="w-full @md/lg:max-w-[945px] ">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadinup"><?= get_the_title(); ?></h1>
        <?php if($fields['content']): ?><p class="paragraph paragraph-xl paragraph-primary text-balance aos animate-fadinup animate-delay-200"><?= $fields['content'] ?></p><?php endif; ?>
      </div>
    </div>
    <?php if ( $projects->have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[48px] *:md:stagger-3">
        <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
          <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
            <?php echo get_template_part('/components/project', null, array('id' => get_the_ID())); ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php  wp_reset_postdata(); // ← Important ! ?>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>