<?php global $adwp; ?>
<?php
// cover-status
?>
<?php $fields = get_fields(); ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [...get_field('header', 'acf-options-global-fields'), 'theme' => $fields['hero']['cover-status'] === 'default' ? 'text-white' : 'text-dark-blue']); ?>
<main id="index">
  <?php get_template_part('/components/hero/markup', 'hero', $fields['hero']); ?>
  <article class="article">
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  </article>
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>