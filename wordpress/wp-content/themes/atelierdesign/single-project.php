<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="single-event">
  <?php $fields = get_fields(); ?>
  <?php get_template_part('/components/hero-project/markup', 'hero-event', $fields['hero']); ?>
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  <!-- <article class="article">
  </article> -->
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>