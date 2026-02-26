<?php
  global $adwp;
  $content = $args['content'];
  // $link = $args['link'];
  $isCustom = $args['isCustom'];
  $items = $args['items'];
  $themes = $args['themes'];
  $types = $args['types'];

  $project_link = get_field('project-link', 'acf-options-global-fields');

  $queryArgs = array(
    'post_type' => 'project',
    'post_status' => 'publish',
    'posts_per_page' => 3,
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
    
  if (!empty($tax)) {
    $queryArgs['tax_query'] = array_merge(['relation' => 'AND'], $tax);
  }


  $project_query = new WP_Query($queryArgs);
  $projects = $project_query->posts; // Tableau de posts

  if ($isCustom) {
    $projects = $items;
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
          <a href="<?= $project_link ? $project_link['url'] : '/projects/' ?>" class="button button-primary button-flat aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= __('See all projects', 'atelierdesign') ?></span>
          </a>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 md:*:stagger-3 @@:gap-[15px]">
      <?php foreach ($projects as $post) : setup_postdata($post); ?>
        <div class="col-span-1 aos animate-fadeinup stagger-delay-100">
          <?php echo get_template_part('/components/project', null, array('id' => get_the_ID())); ?>
        </div>
      <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<?php wp_reset_postdata(); ?>