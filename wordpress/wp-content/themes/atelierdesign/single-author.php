<?php
/**
 * Single Member Template (CPT: member)
 */
global $adwp;
$fields      = get_fields();
$role        = $fields['role'] ?? '';
$cover        = $fields['cover'] ?? '';
$bio        = $fields['submary'] ?? '';
$types = get_the_terms( get_the_ID(), 'member_type' );
$teamLink = get_field('team-link', 'acf-options-global-fields');
$themes = get_the_terms( get_the_ID(), 'themes' );

if(!$cover) $cover = get_field('team-placeholder', 'acf-options-global-fields') ;

?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [
  ...get_field('header', 'acf-options-global-fields'),
  'theme' => 'text-dark-blue',
]); ?>

<main id="single-member">
  <div class="member-hero @sm:pb-[48px] @md/lg:pb-[88px] @sm:pt-[120px] @md/lg:pt-[210px] relative overflow-hidden">
    <div class="px-container relative z-[10]">
      <div class="grid-base @sm:gap-y-[82px] @md/lg:gap-y-[16px]">
        <div class="col-span-10 col-start-2 md:col-span-6 md:col-start-2 ">
          <div class="member-image aos animate-fadeinup">
            <?= wp_get_attachment_image($cover['ID'], 'large', null, ['class' => 'object-cover w-full h-full image-shadow']); ?>
          </div>
        </div>
        <div class="col-span-12 md:col-span-14 md:col-start-9">
          <div class="member-content flex flex-col @sm:gap-y-[16px] @md/lg:gap-y-[16px] @md/lg:pt-[92px] autoscale-children aos animate-fadeinup md:animate-delay-100">
            <?php if($types): ?>
              <ul  class="flex items-center flex-wrap @@:gap-2  autoscale-children">
                <?php foreach($types as $type): ?>
                  <li>
                    <a href="<?= $teamLink ? rtrim($teamLink['url'], '/')."/".$type->slug : "/team/".$type->slug ?>" class="badge badge-primary badge-filled"><?= $type->name ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            <h1 class="heading heading-2xl aos animate-fadeinup md:animate-delay-200"><?= get_the_title() ?></h1>
            <?php if($role): ?><p class="heading-lg heading-primaryaos animate-fadeinup animate-delay-300"><?= $role ?></p><?php endif; ?>
            <?php if($bio): ?>
              <div class="aos animate-fadeinup animate-delay-400">
              <?php $adwp->get_template_part('_wysiwyg',  array('content' => $bio, 'inside' => true, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
              </div>
            <?php endif; ?>

            <?php if($themes): ?>
              <ul  class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-400 autoscale-children">
                <?php foreach($themes as $i => $theme): ?>
                  <li>
                    <a href="<?= $theme->url ?>" class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] link-underline"><?= $theme->name ?></a>
                  </li>

                  <?php if ($i < count($themes) - 1) : ?>
                    <li class="@@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] flex items-center"><span>/</span></li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </div>

    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[0] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto"/>
    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute bottom-0 left-[50%] translate-x-[-50%] md:left-0  md:translate-x-[-30%] z-[0] scale-[-1] md:translate-x-[-30%] translate-y-[30%] @@:h-[800px] w-auto"/>

  </div>

  <?php
  /**
   * Publications & Projects liés à cet author.
   *
   * ACF stocke les relationship fields sous forme de tableau sérialisé,
   * ex: a:2:{i:0;s:3:"42";i:1;s:3:"99";}
   * La recherche LIKE sur '"ID"' (avec guillemets) cible l'ID exact
   * sans risque de faux positifs (ex: 4 vs 42).
   */
  $author_id = get_the_ID();
  $lm_per_page_projects      = 12; // ← modifiable pour la prod
  $lm_per_page_publications  = 12; // ← modifiable pour la prod
  $meta_query_author = [[
    'key'     => 'author',
    'value'   => '"' . $author_id . '"',
    'compare' => 'LIKE',
  ]];
  $base_args = [
    'post_status' => 'publish',
    'orderby'     => 'date',
    'order'       => 'DESC',
    'meta_query'  => $meta_query_author,
  ];

  $publications_query = new WP_Query( array_merge($base_args, [
    'post_type'      => 'publication',
    'posts_per_page' => $lm_per_page_publications,
    'paged'          => 1,
  ]));
  $has_more_publications = $publications_query->max_num_pages > 1;

  $projects_query = new WP_Query( array_merge($base_args, [
    'post_type'      => 'project',
    'posts_per_page' => $lm_per_page_projects,
    'paged'          => 1,
  ]));
  $has_more_projects = $projects_query->max_num_pages > 1;
  ?>

  <?php
  // Langue courante Polylang (null si Polylang inactif → pas de filtre langue)
  $current_lang = function_exists('pll_current_language') ? pll_current_language() : null;

  // Liens archive : priorité aux ACF options (déjà Polylang-aware via get_field),
  // fallback sur la page utilisant le bon template, filtrée par langue courante.
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

  $project_link = get_field('project-link', 'acf-options-global-fields');
  if ( ! $project_link ) {
    $proj_args = [
      'post_type'   => 'page',
      'meta_key'    => '_wp_page_template',
      'meta_value'  => 'templates/projects.php',
      'numberposts' => 1,
    ];
    if ( $current_lang ) $proj_args['lang'] = $current_lang;
    $proj_pages = get_posts( $proj_args );
    if ( $proj_pages ) $project_link = get_permalink( $proj_pages[0]->ID );
  } elseif ( is_array($project_link) ) {
    $project_link = $project_link['url'];
  }
  ?>

  <?php if ( $projects_query->have_posts() ) : ?>
    <section class="theme-light-grey bg-layout-main py-section">
      <div class="px-container">

        <div class="flex flex-col @sm:gap-y-[16px] md:flex-row md:items-center md:justify-between @sm:mb-[40px] @md/lg:mb-[40px] autoscale-children">
          <h2 class="heading heading-lg heading-primary aos animate-fadeinup"><?= pll__('Projects', 'atelierdesign') ?></h2>
          <?php if ( $project_link ) : ?>
            <a href="<?= esc_url( $project_link ) ?>" class="button button-outline button-primary flex items-center @@:gap-x-[12px] w-fit aos animate-fadeinup animate-delay-200">
              <span class="button-title"><?= pll__('See all projects', 'atelierdesign') ?></span>
            </a>
          <?php endif; ?>
        </div>

        <div
          js-loadmore-section
          data-action="loadmore_author_items"
          data-author-id="<?= $author_id ?>"
          data-item-type="project"
        >
          <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] md:*:stagger-3" js-loadmore-grid>
            <?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
              <div class="aos animate-fadeinup stagger-delay-200">
                <?php get_template_part( '/components/project', null, ['id' => get_the_ID()] ); ?>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
          <?php if ( $has_more_projects ) : ?>
            <div class="flex justify-center @@:mt-[48px]">
              <button type="button" class="button button-flat autoscale bg-yellow hover:bg-dark-blue border-yellow hover:border-dark-blue hover:text-white" js-loadmore-btn>
                <span class="button-title"><?= pll__('Load more', 'atelierdesign') ?></span>
              </button>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </section>
  <?php endif; ?>

  <?php if ( $publications_query->have_posts() ) : ?>
    <section class="theme-white bg-layout-main py-section">
      <div class="px-container">

        <div class="flex flex-col @sm:gap-y-[16px] md:flex-row md:items-center md:justify-between @sm:mb-[40px] @md/lg:mb-[40px] autoscale-children">
          <h2 class="heading heading-lg heading-primary aos animate-fadeinup">Publications</h2>
          <?php if ( $publication_link ) : ?>
            <a href="<?= esc_url( $publication_link ) ?>" class="button button-outline button-primary flex items-center @@:gap-x-[12px] w-fit aos animate-fadeinup md:animate-delay-200">
              <span class="button-title"><?= pll__('See all publications', 'atelierdesign') ?></span>
            </a>
          <?php endif; ?>
        </div>

        <div
          js-loadmore-section
          data-action="loadmore_author_items"
          data-author-id="<?= $author_id ?>"
          data-item-type="publication"
        >
          <div class="grid grid-cols-1 md:grid-cols-2 @@:gap-[15px] md:*:stagger-2" js-loadmore-grid>
            <?php while ( $publications_query->have_posts() ) : $publications_query->the_post(); ?>
              <div class="aos animate-fadeinup stagger-delay-200">
                <?php get_template_part( '/components/publication', null, ['id' => get_the_ID(), 'theme' => 'theme-light-blue'] ); ?>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
          <?php if ( $has_more_publications ) : ?>
            <div class="flex justify-center @@:mt-[48px]">
            <button type="button" class="button button-flat autoscale bg-yellow hover:bg-dark-blue border-yellow hover:border-dark-blue hover:text-white" js-loadmore-btn>
            <span class="button-title"><?= pll__('Load more', 'atelierdesign') ?></span>
              </button>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </section>
  <?php endif; ?>

</main>

<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
