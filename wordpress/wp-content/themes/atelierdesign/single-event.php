<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="single-event">
  <?php $fields = get_fields();
      if (!$fields['cover']) $fields['cover-status'] = 'none';
      $bkg = [
        'fit' => '
          <div class="absolute bottom-0 left-[--left-line] w-[1px] h-full bg-dark-blue opacity-20 z-[0] md:h-[--hero-h] mm-sm:hidden"></div>
          <img src="'.get_template_directory_uri().'/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
          <img src="'.get_template_directory_uri().'/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] translate-y-[30%] @@:h-[800px] w-auto"/>        
        ',
        'none' => '
          <div class="absolute bottom-0 left-[--left-line] w-[1px] h-full bg-dark-blue opacity-20 z-[0] md:h-[--hero-h] mm-sm:hidden"></div>
          <img src="'.get_template_directory_uri().'/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>
          <img src="'.get_template_directory_uri().'/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] translate-y-[30%] @@:h-[800px] w-auto"/>
        ',
        'fill' => ' <img src="'.get_template_directory_uri().'/assets/gradient.jpg" class="absolute @sm:bottom-[400px] md:bottom-0 left-[50%] translate-x-[-60%] md:left-0  md:translate-x-[-30%] z-[-1] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>',
      ];

  get_template_part('/components/hero-event/markup', 'hero-event', ['context' => $bkg[$fields['cover-status']]]); ?>
    <?php $adwp->render_flexible_layout($fields['flexible-layout']); ?>
  <!-- <article class="article">
  </article> -->
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>