<?php global $adwp; ?>
<?php
$fields = get_fields() ?: [];
$hero = is_array($fields['hero'] ?? null) ? $fields['hero'] : [];
$hero['social'] = true;
?>
<?php get_header(); ?>
<?php 
  get_template_part(
    '/components/header/markup', 
    'header', 
    [
      ...get_field('header', 'acf-options-global-fields'), 
      'theme' => ($hero['cover-status'] ?? null) === 'default' ? 'text-white' : 'text-dark-blue',
      'isBlendMode' => ($hero['cover-status'] ?? null) === 'fill',
    ]
  );
?>
<main id="index">
  <?php
    get_template_part('/components/hero/markup', null, $hero);
  ?>
  <article class="article relative">
    <!-- SOCIAL HERE -->
    
    <?php $adwp->render_flexible_layout($fields['flexible-layout'] ?? []); ?>
  </article>
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'] ?? null, 'cta' => $fields['cta'] ?? null]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>