<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('src/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="index">
  <?php $fields = get_fields(); ?>
  <article class="article">
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  </article>
</main>
<?php get_template_part('src/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>