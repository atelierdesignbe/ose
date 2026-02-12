<?php
  global $adwp;
  $content = $args['content'];
  $link = $args['link'];
  $isCustom = $args['isCustom'];
  $items = $args['items'];
  $themes = $args['themes'];
  $cover = $args['cover'];
  $types = $args['types'];

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
  
  if(sizeof($tax) > 0) {
    $queryArgs['tax_query'] = $tax;
  }

  $projects_query = new WP_Query($queryArgs);
  $projects = $projects_query->posts; // Tableau de posts

  if ($isCustom) {
    $projects = $items;
  }

?>
<div class="py-section bg-layout-main theme-yellow relative">
  <div class="px-container relative z-[2]">
      <div class="flex flex-col md:flex-row @@:gap-y-[24px] justify-between items-start">
        <div class="flex flex-col @@:gap-y-[24px] items-start  @md/lg:max-w-[638px]">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $content, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <?php if($link): ?>
          <a href="<?= $link['url'] ?>" class="button button-primary button-flat aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $link['title'] ?></span>
          </a>
        <?php endif; ?>
      </div>
      <?php if ( $projects ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[44px] *:md:stagger-3">
          <?php foreach ($projects as $post) : setup_postdata($post); ?>            
            <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
              <?php echo get_template_part('/components/project', null, array('id' => get_the_ID())); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    
  </div>
  <?php if($cover): ?>
    <div class="absolute inset-0 parallax-image-wrapper z-[1]">
      <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'parallax-image object-cover w-full h-full mix-blend-hard-light z-[1] relative']) ?>
      <div class="absolute inset-0 z-[0] bg-yellow"></div>
    </div>
  <?php endif; ?>
</div>