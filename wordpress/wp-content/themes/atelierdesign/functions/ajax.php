<?php
add_action('wp_ajax_filter_project', 'ajax_filter_project');
add_action('wp_ajax_nopriv_filter_project', 'ajax_filter_project');

add_action('wp_ajax_filter_publication', 'ajax_filter_publication');
add_action('wp_ajax_nopriv_filter_publication', 'ajax_filter_publication');

add_action('wp_ajax_loadmore_author_items',        'ajax_loadmore_author_items');
add_action('wp_ajax_nopriv_loadmore_author_items', 'ajax_loadmore_author_items');

add_action('wp_ajax_loadmore_section_projects',        'ajax_loadmore_section_projects');
add_action('wp_ajax_nopriv_loadmore_section_projects', 'ajax_loadmore_section_projects');

add_action('wp_ajax_loadmore_past_events',        'ajax_loadmore_past_events');
add_action('wp_ajax_nopriv_loadmore_past_events', 'ajax_loadmore_past_events');

// ── Load More : items d'un author (projects ou publications) ─────────────────
function ajax_loadmore_author_items() {
  $author_id = (int) ($_POST['authorId'] ?? 0);
  $item_type = in_array( $_POST['itemType'] ?? '', ['project', 'publication'] )
    ? $_POST['itemType'] : 'publication';
  $page      = max(2, (int) ($_POST['page'] ?? 2));
  $per_page  = 12; // ← modifiable pour la prod

  if ( ! $author_id ) wp_send_json_error('Missing authorId');

  $meta_query = [[
    'key'     => 'author',
    'value'   => '"' . $author_id . '"',
    'compare' => 'LIKE',
  ]];

  $query = new WP_Query([
    'post_type'      => $item_type,
    'posts_per_page' => $per_page,
    'paged'          => $page,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => $meta_query,
  ]);

  ob_start();
  while ( $query->have_posts() ) : $query->the_post();
    echo '<div class="aos animate-fadeinup">';
    if ( $item_type === 'project' ) {
      get_template_part('/components/project', null, ['id' => get_the_ID()]);
    } else {
      get_template_part('/components/publication', null, ['id' => get_the_ID(), 'theme' => 'theme-light-blue']);
    }
    echo '</div>';
  endwhile;
  wp_reset_postdata();
  $html = ob_get_clean();

  wp_send_json([
    'html'    => $html,
    'hasMore' => $query->max_num_pages > $page,
  ]);
}

// ── Load More : sections ongoing / completed de la page Projects ─────────────
function ajax_loadmore_section_projects() {
  $section      = ( $_POST['section'] ?? '' ) === 'ongoing' ? 'ongoing' : 'completed';
  $page         = max(2, (int) ($_POST['page'] ?? 2));
  $per_page     = 12;
  $current_year = (int) date('Y');

  // ── IDs concluded : par date + is_completed ──────────────────────────────
  $date_completed_ids = get_posts([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [
      'relation' => 'AND',
      [ 'key' => 'year_end', 'value' => $current_year, 'compare' => '<', 'type' => 'NUMERIC' ],
      [ 'key' => 'year_end', 'value' => '', 'compare' => '!=' ],
    ],
  ]);
  $manually_completed_ids = get_posts([
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'meta_query'     => [[ 'key' => 'is_completed', 'value' => '1', 'compare' => '=' ]],
  ]);
  $all_completed_ids = array_unique( array_merge(
    array_map('intval', $date_completed_ids ?: []),
    array_map('intval', $manually_completed_ids ?: [])
  ));
  $exclude_from_ongoing = ! empty( $all_completed_ids ) ? $all_completed_ids : [0];

  if ( $section === 'ongoing' ) {
    $query = new WP_Query([
      'post_type'      => 'project',
      'posts_per_page' => $per_page,
      'paged'          => $page,
      'post_status'    => 'publish',
      'meta_key'       => 'year_start',
      'orderby'        => 'meta_value_num',
      'order'          => 'DESC',
      'post__not_in'   => $exclude_from_ongoing,
      'meta_query'     => [
        'relation' => 'AND',
        [ 'key' => 'year_start', 'compare' => 'EXISTS' ],
        [ 'key' => 'year_start', 'value' => '', 'compare' => '!=' ],
      ],
    ]);
  } else {

    if ( empty( $all_completed_ids ) ) {
      wp_send_json([ 'html' => '', 'hasMore' => false ]);
      return;
    }

    $query = new WP_Query([
      'post_type'      => 'project',
      'posts_per_page' => $per_page,
      'paged'          => $page,
      'post_status'    => 'publish',
      'meta_key'       => 'year_start',
      'orderby'        => 'meta_value_num',
      'order'          => 'DESC',
      'post__in'       => $all_completed_ids,
    ]);
  }

  ob_start();
  while ( $query->have_posts() ) : $query->the_post();
    echo '<div class="col-span-1 aos animate-fadeinup stagger-delay-200">';
    get_template_part('/components/project', null, ['id' => get_the_ID()]);
    echo '</div>';
  endwhile;
  wp_reset_postdata();
  $html = ob_get_clean();

  wp_send_json([
    'html'    => $html,
    'hasMore' => $query->max_num_pages > $page,
  ]);
}

// ── Load More : past events ───────────────────────────────────────────────────
function ajax_loadmore_past_events() {
  $page     = max(2, (int) ($_POST['page'] ?? 2));
  $per_page = 12;

  // Valeur du jour : même format que le meta ACF (YYYYMMDD numérique)
  $today = (int) date('Ymd');

  $query = new WP_Query([
    'post_type'      => 'event',
    'post_status'    => 'publish',
    'posts_per_page' => $per_page,
    'paged'          => $page,
    'meta_key'       => 'date_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'meta_query'     => [[
      'key'     => 'date_start',
      'value'   => $today,
      'compare' => '<',
      'type'    => 'NUMERIC',
    ]],
  ]);

  if ( ! $query->have_posts() ) {
    wp_send_json([ 'html' => '', 'hasMore' => false ]);
    return;
  }

  ob_start();
  while ( $query->have_posts() ) : $query->the_post();
    echo '<div class="col-span-1 aos animate-fadeinup stagger-delay-200">';
    get_template_part('components/event', null, ['id' => get_the_ID(), 'theme' => 'blue']);
    echo '</div>';
  endwhile;
  wp_reset_postdata();
  $html = ob_get_clean();

  wp_send_json([
    'html'    => $html,
    'hasMore' => $query->max_num_pages > $page,
  ]);
}

function get_filtered_term_counts($taxonomy, $base_filters, $exclude_tax = null, $cpt = 'post', $year = null, $author = null) {
  $tax_query = array_filter($base_filters, function($t) use ($exclude_tax) {
    return !isset($t['taxonomy']) || $t['taxonomy'] !== $exclude_tax;
  });

  $terms = get_terms([
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
  ]);

  $counts = [];

  foreach ($terms as $term) {
    $tax_query_for_count = $tax_query;
    $tax_query_for_count[] = [
      'taxonomy' => $taxonomy,
      'field'    => 'slug', // ← term_id → slug
      'terms'    => [$term->slug], // ← term_id → slug
    ];

    $query_args = [
      'post_type'      => $cpt,
      'fields'         => 'ids',
      'posts_per_page' => 1,
      'tax_query'      => $tax_query_for_count,
    ];

    $meta_query = [];
    
    if ($year) {
      $meta_query[] = [
        'key'     => 'date_start',
        'value'   => $year,
        'compare' => 'LIKE',
      ];
    }
    
    if ($author) {
      $meta_query[] = [
        'key'     => 'author',
        'value'   => '"' . $author . '"',
        'compare' => 'LIKE',
      ];
    }
    
    if (!empty($meta_query)) {
      $query_args['meta_query'] = $meta_query;
    }

    $count_query = new WP_Query($query_args);

    $counts[$term->slug] = $count_query->found_posts; // ← term_id → slug comme clé
    wp_reset_postdata();
  }

  return $counts;
}

function get_filtered_year_counts($base_filters, $cpt = 'post') {
  // Projects utilisent year_start (number), les autres utilisent date_start (date d-m-Y)
  $is_project = ($cpt === 'project');

  // ✅ ÉTAPE 1 : Récupère TOUTES les années existantes (sans filtres)
  $all_years_query = new WP_Query([
    'post_type'      => $cpt,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ]);

  $all_years = [];
  foreach ($all_years_query->posts as $post_id) {
    if ($is_project) {
      $year_s = (int) get_field('year_start', $post_id);
      $year_e = (int) get_field('year_end',   $post_id);
      if ( $year_s ) {
        // Génère toutes les années de la plage [year_start, year_end]
        $end = $year_e ?: $year_s;
        for ($y = $year_s; $y <= $end; $y++) {
          if (!in_array($y, $all_years)) $all_years[] = $y;
        }
      }
    } else {
      $date = get_field('date_start', $post_id);
      $year = $date ? (int) explode('-', $date)[2] : null;
      if ($year && !in_array($year, $all_years)) $all_years[] = $year;
    }
  }

  $year_counts = array_fill_keys($all_years, 0);
  wp_reset_postdata();

  // ✅ ÉTAPE 2 : Applique les filtres et compte
  $query_args = [
    'post_type'      => $cpt,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ];

  if (!empty($base_filters)) {
    $query_args['tax_query'] = $base_filters;
  }

  $filtered_posts = new WP_Query($query_args);

  foreach ($filtered_posts->posts as $post_id) {
    if ($is_project) {
      $year_s = (int) get_field('year_start', $post_id);
      $year_e = (int) get_field('year_end',   $post_id);
      if ( $year_s ) {
        $end = $year_e ?: $year_s;
        for ($y = $year_s; $y <= $end; $y++) {
          if (isset($year_counts[$y])) $year_counts[$y]++;
        }
      }
    } else {
      $date = get_field('date_start', $post_id);
      $year = $date ? (int) explode('-', $date)[2] : null;
      if ($year && isset($year_counts[$year])) $year_counts[$year]++;
    }
  }

  wp_reset_postdata();
  krsort($year_counts);

  return $year_counts;
}

function get_filtered_author_counts($base_filters, $cpt = 'publication') {
  $query_args = [
    'post_type'      => $cpt,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ];

  if (!empty($base_filters)) {
    $query_args['tax_query'] = $base_filters;
  }
  
  $all_posts = new WP_Query($query_args);
  $author_counts = [];

  foreach ($all_posts->posts as $post_id) {
    $authors = get_field('author', $post_id);
    
    if ($authors && is_array($authors)) {
      foreach ($authors as $author) {
        $author_id = $author->ID;
        
        if (!isset($author_counts[$author_id])) {
          $author_counts[$author_id] = [
            'id' => $author_id,
            'name' => $author->post_title,
            'count' => 0,
          ];
        }
        $author_counts[$author_id]['count']++;
      }
    }
  }

  wp_reset_postdata();
  
  usort($author_counts, function($a, $b) {
    return strcmp($a['name'], $b['name']);
  });
  
  return $author_counts;
}

/**
 * PROJECT
 */

function ajax_filter_project() {
  $filter        = $_POST['filters'] ?? [];
  $filtersDecode = json_decode( stripslashes( $filter ) );
  $current_year  = (int) date('Y');

  // ── 1. Filtre taxonomie ───────────────────────────────────────────────────
  $tax_query = ['relation' => 'AND'];
  if ( ! empty( $filtersDecode->themes ) ) {
    $tax_query[] = [
      'taxonomy' => 'themes',
      'field'    => 'slug',
      'terms'    => $filtersDecode->themes,
    ];
  }
  $year_filter = ! empty( $filtersDecode->period ) ? (int) $filtersDecode->period : null;

  // ── 2. Récupère tous les IDs de projets (avec filtre tax si actif) ────────
  $ids_args = [
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ];
  if ( count( $tax_query ) > 1 ) $ids_args['tax_query'] = $tax_query;
  $all_ids = array_map( 'intval', get_posts( $ids_args ) ?: [] );

  // ── 3. Filtre par année en PHP (logique conditionnelle correcte) ──────────
  //   • Pas de year_end → l'année filtrée doit être égale à year_start
  //   • Avec year_end  → l'année filtrée doit être dans [year_start, year_end]
  if ( $year_filter ) {
    $all_ids = array_values( array_filter( $all_ids, function( int $id ) use ( $year_filter ): bool {
      $ys = (int) get_post_meta( $id, 'year_start', true );
      $ye = (int) get_post_meta( $id, 'year_end',   true );
      if ( ! $ys ) return false;
      return $ye ? ( $ys <= $year_filter && $year_filter <= $ye )
                 : ( $ys === $year_filter );
    }));
  }

  // ── 4. Séparation ongoing / completed en PHP ──────────────────────────────
  $ongoing_ids   = [];
  $completed_ids = [];

  foreach ( $all_ids as $id ) {
    $is_completed = get_post_meta( $id, 'is_completed', true ) === '1';
    if ( $is_completed ) {
      $completed_ids[] = $id;
      continue;
    }
    $ye = (int) get_post_meta( $id, 'year_end', true );
    if ( ! $ye || $ye >= $current_year ) {
      $ongoing_ids[] = $id;
    } else {
      $completed_ids[] = $id;
    }
  }

  // ── 5. WP_Query uniquement pour l'ordre ───────────────────────────────────
  $order_args = [
    'post_type'      => 'project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'year_start',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
  ];

  $query_ongoing   = ! empty( $ongoing_ids )
    ? new WP_Query( $order_args + ['post__in' => $ongoing_ids] )
    : null;

  $query_completed = ! empty( $completed_ids )
    ? new WP_Query( $order_args + ['post__in' => $completed_ids] )
    : null;

  // ── 6. Rendu HTML ─────────────────────────────────────────────────────────
  ob_start();

  if ( $query_ongoing && $query_ongoing->have_posts() ) : ?>
    <div class="container @sm:pb-[32px] @md/lg:pb-[80px]">
      <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
        <h2 class="heading heading-xl heading-primary"><?= pll__('Ongoing projects', 'atelierdesign') ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3">
          <?php while ( $query_ongoing->have_posts() ) : $query_ongoing->the_post(); ?>
            <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
              <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  <?php else : ?>
    <div class="container">
      <p class="paragraph paragraph-lg paragraph-primary @sm:pb-[32px] @md/lg:pb-[42px] aos animate-fadeinup">
        <?= __('There are currently no ongoing projects.', 'atelierdesign'); ?>
      </p>
    </div>
  <?php endif;
  wp_reset_postdata();

  if ( $query_completed && $query_completed->have_posts() ) : ?>
    <div class="theme-dark-blue bg-layout-main @@:py-[80px]">
      <div class="container">
        <div class="flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[32px]">
          <h2 class="heading heading-xl heading-primary"><?= pll__('Concluded projects', 'atelierdesign') ?></h2>
          <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] *:md:stagger-3">
            <?php while ( $query_completed->have_posts() ) : $query_completed->the_post(); ?>
              <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
                <?php echo get_template_part('/components/project', null, ['id' => get_the_ID()]); ?>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif;
  wp_reset_postdata();

  $html = ob_get_clean();

  wp_send_json([
    'html'          => $html,
    'filtersDecode' => $filtersDecode,
    'hasPagination' => false,
    'filter'        => [
      'themes' => get_filtered_term_counts('themes', $tax_query, 'themes', 'project', $year_filter),
      'period' => get_filtered_year_counts( $tax_query, 'project' ),
    ],
  ]);
}

function ajax_filter_publication() {
  $filter = $_POST['filters'] ?? [];
  $filtersDecode = json_decode(stripslashes($filter));
  $paged = $_POST['page'] ?? 1;

  $tax_query = ['relation' => 'AND'];
  $meta_query = [];

  if (!empty($filtersDecode->types)) {
    $tax_query[] = [
      'taxonomy' => 'types',
      'field' => 'slug',
      'terms' => $filtersDecode->types,
    ];
  }

  if (!empty($filtersDecode->themes)) {
    $tax_query[] = [
      'taxonomy' => 'themes',
      'field' => 'slug',
      'terms' => $filtersDecode->themes,
    ];
  }

  if (!empty($filtersDecode->projects)) {
    $tax_query[] = [
      'taxonomy' => 'projects',
      'field' => 'slug',
      'terms' => $filtersDecode->projects,
    ];
  }

  if (!empty($filtersDecode->authors)) {
  
    $meta_query[] = [
      'key' => 'author',
      'value' => '"' . $filtersDecode->authors . '"',
      'compare' => 'LIKE',
    ];
  }

  // Toujours exclure les publications masquées (hidden = 1)
  $meta_query[] = [
    'relation' => 'OR',
    [ 'key' => 'hidden', 'compare' => 'NOT EXISTS' ],
    [ 'key' => 'hidden', 'value' => '1', 'compare' => '!=' ],
  ];

  $args = [
    'paged'          => $paged,
    'post_type'      => 'publication',
    'post_status'    => 'publish',
    'posts_per_page' => 16,
    'meta_key'       => 'date_start',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'tax_query'      => count($tax_query) > 1 ? $tax_query : '',
    'meta_query'     => $meta_query,
  ];

  $query = new WP_Query($args);
  $posts = $query->posts;

  ob_start();
  if ($posts): 
    foreach($posts as $i => $post):
      echo '<div class="col-span-1 aos animate-fadeinup stagger-delay-200">';
      echo get_template_part('/components/publication', null, array('id' => $post->ID));
      echo '</div>';
    endforeach;
  endif; 

  $html = ob_get_clean();

  // $selected_year = !empty($filtersDecode->period) ? $filtersDecode->period : null;
  $selected_author = !empty($filtersDecode->authors) ? $filtersDecode->authors : null;

  wp_send_json([
    'html' => $html,
    'filtersDecode' => $filtersDecode,
    'hasPagination' => $query->max_num_pages > $paged,
    'year' => $selected_year,
    'filter' => [
      'themes' => get_filtered_term_counts('themes', $tax_query, 'themes', 'publication', null, $selected_author),
      'types' => get_filtered_term_counts('types', $tax_query, 'types', 'publication', null, $selected_author),
      'projects' => get_filtered_term_counts('projects', $tax_query, 'projects', 'publication', null, $selected_author),
      'authors' => get_filtered_author_counts($tax_query, 'publication'),
    ]
  ]);
}

?>