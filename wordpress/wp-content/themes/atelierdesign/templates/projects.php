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

  // ── Années disponibles pour le filtre (plage year_start → year_end de chaque projet) ──
  $all_ids_query = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ]);
  $years = [];
  foreach ( $all_ids_query->posts as $pid ) {
    $ys = (int) get_field('year_start', $pid);
    $ye = (int) get_field('year_end',   $pid);
    if ( ! $ys ) continue;
    $end = $ye ?: $ys;
    for ( $y = $ys; $y <= $end; $y++ ) {
      if ( ! in_array($y, $years) ) $years[] = $y;
    }
  }
  rsort($years); // DESC
  wp_reset_postdata();

  $themes = get_term_ids_for_cpt('themes', ['project']);

  $lm_per_page = 12; // ← items par page (load more)

  // ── Section 1 : projets en cours ────────────────────────────────────────
  $projects_ongoing = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => $lm_per_page,
    'paged'          => 1,
    'meta_key'       => 'year_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [
      'relation' => 'AND',
      [ 'key' => 'year_start', 'compare' => 'EXISTS' ],
      [ 'key' => 'year_start', 'value' => '', 'compare' => '!=' ],
      [
        'relation' => 'OR',
        [ 'key' => 'year_end', 'compare' => 'NOT EXISTS' ],
        [ 'key' => 'year_end', 'value' => '', 'compare' => '=' ],
        [ 'key' => 'year_end', 'value' => $current_year, 'compare' => '>=', 'type' => 'NUMERIC' ],
      ],
      // Exclure les projets manuellement marqués comme terminés
      [
        'relation' => 'OR',
        [ 'key' => 'is_completed', 'compare' => 'NOT EXISTS' ],
        [ 'key' => 'is_completed', 'value' => '1', 'compare' => '!=' ],
      ],
    ],
  ]);
  $has_more_ongoing = $projects_ongoing->max_num_pages > 1;

  // ── Section 2 : projets terminés ────────────────────────────────────────
  $projects_completed = new WP_Query([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => $lm_per_page,
    'paged'          => 1,
    'meta_key'       => 'year_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [
      'relation' => 'OR',
      [
        'relation' => 'AND',
        [ 'key' => 'year_end', 'value' => $current_year, 'compare' => '<', 'type' => 'NUMERIC' ],
        [ 'key' => 'year_end', 'value' => '', 'compare' => '!=' ],
      ],
      [ 'key' => 'year_start', 'compare' => 'NOT EXISTS' ],
      [ 'key' => 'year_start', 'value' => '', 'compare' => '=' ],
      // Projets manuellement marqués comme terminés (quelle que soit la date)
      [ 'key' => 'is_completed', 'value' => '1', 'compare' => '=' ],
    ],
  ]);
  $has_more_completed = $projects_completed->max_num_pages > 1;
?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>

<main id="projects" js-ajax="project" class="relative overflow-hidden">

  <div class="container @sm:pt-[120px] @md/lg:pt-[220px] @lg:pt-[144px] @@:pb-[42px]">
    <div class="w-full @md/lg:max-w-[945px]">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadeinup">
        <?= pll__('Ongoing projects', 'atelierdesign') ?>
        </h1>
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

  <!-- ══ Sections projects (rendu initial + remplacement AJAX) ══ -->
  <div js-ajax-results>

    <!-- Section 1 : Projets en cours -->
    <?php if ( $projects_ongoing->have_posts() ) : ?>
      <div class="container @sm:pb-[32px] @md/lg:pb-[80px]">
        <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
          <!-- <h2 class="heading heading-xl heading-primary aos animate-fadeinup"><?= pll__('Ongoing projects', 'atelierdesign') ?></h2> -->
          <div js-loadmore-section data-action="loadmore_section_projects" data-section="ongoing">
            <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3" js-loadmore-grid>
              <?php while ( $projects_ongoing->have_posts() ) : $projects_ongoing->the_post(); ?>
                <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
                  <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php if ( $has_more_ongoing ) : ?>
              <div class="flex justify-center @@:mt-[48px]">
              <button type="button" class="button button-flat autoscale bg-yellow hover:bg-dark-blue border-yellow hover:border-dark-blue hover:text-white" js-loadmore-btn>
                <span class="button-title"><?= pll__('Load more', 'atelierdesign') ?></span>
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <p class="paragraph paragraph-lg paragraph-primary @sm:pb-[32px] @md/lg:pb-[42px]  aos animate-fadeinup">
          <?= __('There are currently no ongoing projects.', 'atelierdesign'); ?>
        </p>
      </div>
    <?php endif; ?>

    <!-- Section 2 : Projets terminés -->
    <?php if ( $projects_completed->have_posts() ) : ?>
      <div class="theme-dark-blue bg-layout-main @@:py-[80px]">
        <div class="container">
          <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
            <h2 class="heading heading-xl heading-primary aos animate-fadeinup"><?= pll__('Concluded projects', 'atelierdesign') ?></h2>
            <div js-loadmore-section data-action="loadmore_section_projects" data-section="completed">
              <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3" js-loadmore-grid>
                <?php while ( $projects_completed->have_posts() ) : $projects_completed->the_post(); ?>
                  <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
                    <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
                  </div>
                <?php endwhile; wp_reset_postdata(); ?>
              </div>
              <?php if ( $has_more_completed ) : ?>
                <div class="flex justify-center @@:mt-[48px]">
                <button type="button" class="button button-flat autoscale bg-yellow hover:bg-white border-yellow hover:border-white hover:text-dark-blue" js-loadmore-btn>
                  <span class="button-title"><?= pll__('Load more', 'atelierdesign') ?></span>
                  </button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>

  </div><!-- /js-ajax-results -->

  <!-- Pagination (non utilisée pour les projets) -->
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
