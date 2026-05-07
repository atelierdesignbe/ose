<?php global $adwp; ?>
<?php get_header(); ?>
<?php 
  $fields = get_fields();
   
  get_template_part(
    '/components/header/markup', 
    'header', 
    [
      ...get_field('header', 'acf-options-global-fields'), 
    ]
  );
?>
<main id="single-publication">
  <?php 

  if(!$fields['cover']) $fields['cover'] = get_field('publication-placeholder', 'acf-options-global-fields') ;

  get_template_part('/components/hero-publication/markup', 'hero-publication') ?>
  <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  <!-- <article class="article">
  </article> -->
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>