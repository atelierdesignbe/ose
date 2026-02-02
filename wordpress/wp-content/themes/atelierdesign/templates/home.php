<?php

/**
 * Template Name: Home
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header'); ?>
<main id="template-home">
  <?php $fields = get_fields(); ?>
  <article class="article">
    <?php get_template_part('src/components/hero/markup', 'hero', $fields['hero']); ?>
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer'); ?>
<?php get_footer(); ?>