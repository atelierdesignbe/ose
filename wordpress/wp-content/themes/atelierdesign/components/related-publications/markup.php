<?php
  global $adwp;
  $content = $args['content'];
  // $link = $args['link'];
  $isCustom = $args['isCustom'];
  $items = $args['items'];
  $themes = $args['themes'];
  $types = $args['types'];
  $projects = $args['projects'];
  $authors  = $args['authors']; // tableau d'IDs

  $publication_link = get_field('publication-link', 'acf-options-global-fields');


  $queryArgs = array(
    'post_type' => 'publication',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'DESC',
  );

  $tax = array();

  if(!$isCustom && $themes) {
    $tax[] = array(
      'taxonomy' => 'themes', // Le slug de ta taxonomie
      'field' => 'term_id', // ou 'slug', 'name'
      'terms' => $themes, // L'ID du terme (ou array d'IDs)
    );
  }

  if(!$isCustom && $types) {
    $tax[] = array(
      'taxonomy' => 'types', // Le slug de ta taxonomie
      'field' => 'term_id', // ou 'slug', 'name'
      'terms' => $types, // L'ID du terme (ou array d'IDs)
    );
  }

  if (!$isCustom && $projects) {
    $tax[] = [
        'taxonomy' => 'projects',
        'field'    => 'term_id',
        'terms'    => $projects,
    ];
  }

    
  if (!empty($tax)) {
    $queryArgs['tax_query'] = array_merge(['relation' => 'AND'], $tax);
  }

  if (!$isCustom && !empty($authors)) {
    $meta_query = ['relation' => 'OR'];

    foreach ($authors as $author_id) {
        $meta_query[] = [
            'key'     => 'author',
            'value'   => '"' . $author_id . '"',
            'compare' => 'LIKE',
        ];
    }

    $queryArgs['meta_query'] = $meta_query;
  }


  $publications_query = new WP_Query($queryArgs);
  $publications = $publications_query->posts; // Tableau de posts

  if ($isCustom) {
    $publications = $items;
  }
?>

<div class="relative py-section theme-white bg-layout-main">
  <div class="px-container">
    <div class="flex flex-col @sm:gap-y-[32px] @md/lg:gap-y-[40px]">
      <div class="flex flex-col md:flex-row @@:gap-[24px] md:justify-between items-start autoscale-children">
        <div class="flex-auto">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $content, 'inside' => true, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="flex-none">
          <a href="<?= $publication_link ? $publication_link['url'] : '/publications/' ?>" class="button button-primary button-flat aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= __('See all publications', 'atelierdesign') ?></span>
          </a>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 md:*:stagger-2 @@:gap-[15px]">
      <?php foreach ($publications as $post) : setup_postdata($post); ?>
        <div class="col-span-1 aos animate-fadeinup stagger-delay-100">
          <?php echo get_template_part('/components/publication', null, array('id' => get_the_ID())); ?>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php wp_reset_postdata(); ?>