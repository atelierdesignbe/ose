<?php
add_action('wp_ajax_filter_project', 'ajax_filter_project');
add_action('wp_ajax_nopriv_filter_project', 'ajax_filter_project');

add_action('wp_ajax_filter_publication', 'ajax_filter_publication');
add_action('wp_ajax_nopriv_filter_publication', 'ajax_filter_publication');

function get_filtered_term_counts($taxonomy, $base_filters, $exclude_tax = null, $cpt = 'post', $year = null, $author = null) {
  // Supprimer la taxonomie qu'on est en train de compter
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
      'field'    => 'term_id',
      'terms'    => [$term->term_id],
    ];

    $query_args = [
      'post_type'      => $cpt,
      'fields'         => 'ids',
      'posts_per_page' => 1,
      'tax_query'      => $tax_query_for_count,
    ];

    // ✅ Construit le meta_query avec année ET/OU auteur
    $meta_query = [];
    
    if ($year) {
      $meta_query[] = [
        'key'     => 'date_start',
        'value'   =>  $year,
        'compare' => 'LIKE',
      ];
    }
    
    if ($author) {
      $meta_query[] = [
        'key'     => 'author',
        'value'   => '"' . $author . '"', // L'ID de l'auteur avec guillemets
        'compare' => 'LIKE',
      ];
    }
    
    // Ajoute meta_query seulement si nécessaire
    if (!empty($meta_query)) {
      $query_args['meta_query'] = $meta_query;
    }

    $count_query = new WP_Query($query_args);

    $counts[$term->term_id] = $count_query->found_posts;
    wp_reset_postdata();
  }

  return $counts;
}

function get_filtered_year_counts($base_filters, $cpt = 'post') {
  // ✅ ÉTAPE 1 : Récupère TOUTES les années existantes (sans filtres)
  $all_years_query = new WP_Query([
    'post_type'      => $cpt,
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
  ]);
  
  $all_years = [];
  foreach ($all_years_query->posts as $post_id) {
    $date = get_field('date_start', $post_id);
    if ($date) {
      $year = explode('-', $date)[2];
      if (!in_array($year, $all_years)) {
        $all_years[] = $year;
      }
    }
  }
  
  // Initialise toutes les années à 0
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
  
  // Compte les posts filtrés par année
  foreach ($filtered_posts->posts as $post_id) {
    $date = get_field('date_start', $post_id);
    if ($date) {
      $year = explode('-', $date)[2];
      if (isset($year_counts[$year])) {
        $year_counts[$year]++;
      }
    }
  }

  wp_reset_postdata();
  
  // Trie les années (plus récent en premier)
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
  $filter = $_POST['filters'] ?? [];
  $filtersDecode = json_decode(stripslashes($filter));
  $paged = $_POST['page'] ?? 1;

  $tax_query = ['relation' => 'AND'];
  $meta_query = [];

  if (!empty($filtersDecode->types)) {
    $tax_query[] = [
      'taxonomy' => 'types',
      'field' => 'term_id',
      'terms' => $filtersDecode->types,
    ];
  }

  if (!empty($filtersDecode->themes)) {
    $tax_query[] = [
      'taxonomy' => 'themes',
      'field' => 'term_id',
      'terms' => $filtersDecode->themes,
    ];
  }

  if (!empty($filtersDecode->period)) {
    $meta_query[] = [
      'key' => 'date_start',
      'value' => $filtersDecode->period,
      'compare' => 'LIKE',
    ];
  }

  $args = [
    'paged' => $paged,
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => 18,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'tax_query' => count($tax_query) > 1 ? $tax_query : '',
    'meta_query' => count($meta_query) >= 1 ? $meta_query : '',
  ];

  $query = new WP_Query($args);
  $posts = $query->posts;

  ob_start();
  if ($posts): 
    foreach($posts as $i => $post):
      echo '<div class="col-span-1 aos animate-fadeinup stagger-delay-200">';
      echo get_template_part('/components/project', null, array('id' => $post->ID));
      echo '</div>';
    endforeach;
  endif; 

  $html = ob_get_clean();

  $selected_year = !empty($filtersDecode->period) ? $filtersDecode->period : null;

  wp_send_json([
    'html' => $html,
    'filtersDecode' => $filtersDecode,
    'hasPagination' => $query->max_num_pages > $paged,
    'year' => $selected_year,
    'filter' => [
      'themes' => get_filtered_term_counts('themes', $tax_query, 'themes', 'project', $selected_year),
      'types' => get_filtered_term_counts('types', $tax_query, 'types', 'project', $selected_year),
      'period' => get_filtered_year_counts($tax_query, 'project'),
    ]
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
      'field' => 'term_id',
      'terms' => $filtersDecode->types,
    ];
  }

  if (!empty($filtersDecode->themes)) {
    $tax_query[] = [
      'taxonomy' => 'themes',
      'field' => 'term_id',
      'terms' => $filtersDecode->themes,
    ];
  }

  if (!empty($filtersDecode->projects)) {
    $tax_query[] = [
      'taxonomy' => 'projects',
      'field' => 'term_id',
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

  $args = [
    'paged' => $paged,
    'post_type' => 'publication',
    'post_status' => 'publish',
    'posts_per_page' => 16,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'tax_query' => count($tax_query) > 1 ? $tax_query : '',
    'meta_query' => count($meta_query) >= 1 ? $meta_query : '',
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