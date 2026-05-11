<?php global $adwp; ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="single-event">
  <?php 
    $fields = get_fields();

    $publications = $fields['related_publications'];
    get_template_part(
      '/components/header/markup', 
      'header', 
      [
        ...get_field('header', 'acf-options-global-fields'), 
        'isBlendMode' => $fields['cover-status'] === 'fill'
      ]
    );
  ?>

  <?php 
    get_template_part('/components/hero-project/markup', 'hero-project'); 
    $adwp->render_flexible_layout($fields['flexible-layout']);

    $publication_link = get_field('publication-link', 'acf-options-global-fields');
    if ( ! $publication_link ) {
      $pub_args = [
        'post_type'   => 'page',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => 'templates/publications.php',
        'numberposts' => 1,
      ];
      if ( $current_lang ) $pub_args['lang'] = $current_lang;
      $pub_pages = get_posts( $pub_args );
      if ( $pub_pages ) $publication_link = get_permalink( $pub_pages[0]->ID );
    } elseif ( is_array($publication_link) ) {
      $publication_link = $publication_link['url'];
    }
  
  ?>

<?php if($publications): ?>
  <section class="theme-light-grey bg-layout-main py-section">
      <div class="px-container">

        <div class="flex flex-col @sm:gap-y-[16px] md:flex-row md:items-center md:justify-between @sm:mb-[40px] @md/lg:mb-[40px] autoscale-children">
          <h2 class="heading heading-lg heading-primary aos animate-fadeinup"><?= pll__('Related publications', 'atelierdesign') ?></h2>
          <?php if ( $publication_link ) : ?>
            <a
              href="<?= esc_url( $publication_link ) ?>"
              class="button button-outline button-primary flex items-center @@:gap-x-[12px] w-fit"
            >
              <span class="button-title"><?= pll__('See all publications', 'atelierdesign') ?></span>
            </a>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 @@:gap-[15px]">
          <?php foreach ( $publications as $pub ) : ?>
            <div class="aos animate-fadeinup">
              <?php get_template_part( '/components/publication', null, ['id' => $pub->ID, 'theme' => 'theme-light-blue'] ); ?>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section>
<?php endif; ?>

  <!-- <article class="article">
  </article> -->
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>