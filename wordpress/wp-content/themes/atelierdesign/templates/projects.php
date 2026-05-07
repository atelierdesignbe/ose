<?php

/**
 * Template Name: Projects
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php
  $fields       = get_fields();
  $current_year = (int) date('Y');

  // ── Années disponibles pour le filtre (year_start de tous les projets) ──
  $all_ids_query = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ]);
  $years = [];
  foreach ( $all_ids_query->posts as $pid ) {
    $y = get_field('year_start', $pid);
    if ( $y && ! in_array($y, $years) ) $years[] = $y;
  }
  rsort($years); // DESC
  wp_reset_postdata();

  $themes = get_term_ids_for_cpt('themes', ['project']);

  // ── Section 1 : projets en cours ────────────────────────────────────────
  // Règle : year_start renseigné ET (year_end vide OU year_end >= année courante)
  $projects_ongoing = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'year_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [
      'relation' => 'AND',
      // year_start doit exister et ne pas être vide
      [ 'key' => 'year_start', 'compare' => 'EXISTS' ],
      [ 'key' => 'year_start', 'value' => '', 'compare' => '!=' ],
      // year_end vide OU >= année courante
      [
        'relation' => 'OR',
        [ 'key' => 'year_end', 'compare' => 'NOT EXISTS' ],
        [ 'key' => 'year_end', 'value' => '', 'compare' => '=' ],
        [ 'key' => 'year_end', 'value' => $current_year, 'compare' => '>=', 'type' => 'NUMERIC' ],
      ],
    ],
  ]);

  // ── Section 2 : projets terminés ────────────────────────────────────────
  // Règle : year_end < année courante  OU  year_start non renseigné
  $projects_completed = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'year_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [
      'relation' => 'OR',
      // year_end renseigné ET < année courante
      [
        'relation' => 'AND',
        [ 'key' => 'year_end', 'value' => $current_year, 'compare' => '<', 'type' => 'NUMERIC' ],
        [ 'key' => 'year_end', 'value' => '', 'compare' => '!=' ],
      ],
      // ou year_start absent/vide
      [ 'key' => 'year_start', 'compare' => 'NOT EXISTS' ],
      [ 'key' => 'year_start', 'value' => '', 'compare' => '=' ],
    ],
  ]);
?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="projects" js-ajax="project" class="relative overflow-hidden">

  <div class="container @sm:pt-[120px] @md/lg:pt-[220px] @lg:pt-[144px] @@:pb-[78px]">
    <div class="w-full @md/lg:max-w-[945px]">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadeinup"><?= the_title(); ?></h1>
        <?php if ( $fields['content'] ) : ?><p class="paragraph paragraph-xl paragraph-primary text-balance aos animate-fadeinup animate-delay-200"><?= $fields['content'] ?></p><?php endif; ?>
      </div>
    </div>

    <!-- FILTER -->
    <?php if ( $themes || $years ) : ?>
      <div class="filter flex flex-col md:flex-row md:items-center flex-wrap @@:gap-[16px] @@:mt-[60px] aos animate-fadeinup relative z-[1]">
        <p class="filter-label subtitle autoscale text-dark-blue">Filter by</p>
        <div class="flex flex-wrap flex-row mm-sm:justify-between @sm:gap-y-[12px] @md/lg:gap-x-4 filters">

          <?php if ( $themes ) : ?>
            <div class="relative mm-sm:w-[48%] md:w-auto">
              <button type="button" class="button button-outline !rounded-none border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between filter-button" js-expand-button>
                <span class="button-title">Theme</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue @md/lg:min-w-[160px] w-full bg-white text-dark-blue" data-lenis-prevent js-ajax-filter="themes">
                  <?php foreach ( $themes as $theme ) : ?>
                    <li js-expand-item>
                      <button type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn" data-id="<?= $theme->slug ?>" data-name="<?= $theme->name ?>">
                        <span><?= $theme->name ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

          <?php if ( $years ) : ?>
            <div class="relative mm-sm:w-[48%] md:w-auto">
              <button type="button" class="button button-outline !rounded-none border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between filter-button" js-expand-button>
                <span class="button-title">Year</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue @md/lg:min-w-[160px] bg-white text-dark-blue w-full" data-lenis-prevent js-ajax-filter="period">
                  <?php foreach ( $years as $year ) : ?>
                    <li js-expand-item>
                      <button data-id="<?= $year ?>" data-name="<?= $year ?>" type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn">
                        <span><?= $year ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

        </div>
        <div class="filter-reset autoscale-children flex items-center flex-wrap @@:gap-2 relative z-[1]" js-ajax-reset></div>
      </div>
    <?php endif; ?>
  </div><!-- /container -->

  <!-- ══ VUE STATIQUE (deux sections, masquée quand un filtre est actif) ══ -->
  <div js-projects-sections>

    <!-- Section 1 : Projets en cours -->
    <div class="container">
      <?php if ( $projects_ongoing->have_posts() ) : ?>
        <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
          <h2 class="heading heading-xl heading-primary aos animate-fadeinup"><?= pll__('Ongoing projects', 'atelierdesign') ?></h2>
          <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3">
            <?php while ( $projects_ongoing->have_posts() ) : $projects_ongoing->the_post(); ?>
              <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
                <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>

    <!-- Section 2 : Projets terminés -->
    <?php if ( $projects_completed->have_posts() ) : ?>
      <div class="theme-dark-blue bg-layout-main @@:py-[80px] @@:mt-[80px]">
        <div class="container">
          <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
            <h2 class="heading heading-xl heading-primary aos animate-fadeinup"><?= pll__('Concluded projects', 'atelierdesign') ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3">
              <?php while ( $projects_completed->have_posts() ) : $projects_completed->the_post(); ?>
                <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
                  <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        </div>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>

  </div><!-- /js-projects-sections -->

  <!-- ══ VUE AJAX (résultats filtrés, masquée par défaut) ══ -->
  <div class="container" js-ajax-sections style="display:none">
    <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[48px] *:md:stagger-3" js-ajax-results></div>
  </div>

  <!-- Pagination Load more (masquée par défaut, activée par l'AJAX) -->
  <div class="flex items-center justify-center" js-ajax-pagination style="display:none">
    <button type="button" class="button button-flat autoscale @@:mt-[60px]">
      <span class="button-title">Load more</span>
    </button>
  </div>

  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto"/>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>

<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
