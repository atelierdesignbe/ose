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
  <?php $fields = get_fields();
  var_dump($fields);
  ?>
  <?php get_template_part('/components/home-hero/markup', 'home-hero', []); ?>

  <!-- <article class="article">
  </article> -->
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', []); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>