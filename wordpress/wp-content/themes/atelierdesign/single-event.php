<?php global $adwp; ?>
<?php get_header(); ?>
<?php 
  $fields = get_fields();
  get_template_part(
    '/components/header/markup', 
    'header', 
    [
      ...get_field('header', 'acf-options-global-fields'), 
      'isBlendMode' => ($fields['cover-status'] ?? null) === 'fill'
    ]
  );
?>
<main id="single-event">
  <?php 
    get_template_part('/components/hero-event/markup', 'hero-event');
    $adwp->render_flexible_layout($fields['flexible-layout'] ?? []);
  ?>
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'] ?? null, 'cta' => $fields['cta'] ?? null]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>