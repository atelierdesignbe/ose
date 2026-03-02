<?php global $adwp; ?>
<?php
// cover-status
?>
<?php $fields = get_fields(); ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [...get_field('header', 'acf-options-global-fields'), 'theme' => $fields['hero']['cover-status'] === 'default' ? 'text-white' : 'text-dark-blue']); ?>
<main id="index">
  <?php 
    $args = [
      ...$fields['hero'],
      'context' => '
        <div class="absolute inset-0 hero-gradient-header z-[8]"></div>
        <div class="absolute inset-0 bg-layout-main theme-dark-blue opacity-20"></div>
        <div class="absolute inset-0 gradient-fullsize mix-blend-darken"></div>
      '
    ];
    get_template_part('/components/hero/markup', 'hero', $args); 
  ?>
  <article class="article relative">
    <!-- SOCIAL HERE -->
    <div class="absolute @md/lg:top-[-96px] left-0  theme-white bg-layout-main px-container z-10 text-dark-blue hidden md:flex items-center @@:gap-x-[42px] @md/lg:h-[96px] ">
      <div class="aos animate-fadeinup">
        <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
      </div>
    </div>
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  </article>
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>