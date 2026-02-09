<?php
  global $adwp;
  $content = $args['content'];
  $link = $args['link'];
  $isCustom = $args['isCustom'];
  $items = $args['items'];
  $themes = $args['themes'];
  $types = $args['types'];

  $queryArgs = array(
    'post_type' => 'publication',
    'post_status' => 'publish',
    'posts_per_page' => 2,
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

  $publications_query = new WP_Query($queryArgs);
  $publications = $publications_query->posts; // Tableau de posts

  if ($isCustom) {
    $publications = $items;
  }

?>
<div class="relative last-publication">
  <div class="bg-layout-main theme-light-blue">
    <div class="px-container grid grid-base">

      <div class="flex flex-col @@:gap-y-[24px] items-start theme-dark-blue py-section col-span-12 md:col-span-8 z-[1] autoscale-children">
        <?php $adwp->get_template_part('_wysiwyg',  array('content' => $content, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        <?php if($link): ?>
          <a href="<?= $link['url'] ?>" class="button button-primary button-flat aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $link['title'] ?></span>
          </a>
        <?php endif; ?>
      </div>
      <div class="col-span-12 md:col-span-15 md:col-start-10 mm-sm:pt-0 py-section z-[1]">
          <!--  -->
          <?php if ($publications) : ?>
          <div class="flex flex-col @@:gap-y-[15px] *:md:stagger-2">
            <?php foreach ($publications as $post) : setup_postdata($post); ?>
              <div class="aos animate-fadeinup stagger-delay-100">
                <?php echo get_template_part('/components/publication', null, array('id' => get_the_ID(), 'theme' => 'theme-white')); ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
      <div class="absolute top-0 left-0 w-full h-full last-publication-cover "></div>
    </div>
  </div>
</div>