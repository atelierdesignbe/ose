<?php

/**
 * Template Name: Events
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="publications">
  <div class="px-container">
    <div class="w-full @md/lg:max-w-[945px] @@:pt-[144px] @@:pb-[78px]">
      <h1 class="heading heading-2xl heading-primary"><?= get_the_title(); ?></h1>
    </div>
  </div>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>