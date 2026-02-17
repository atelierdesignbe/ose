<?php
  global $adwp;
  $content = $args['content'];
  $link = $args['link'];
  $isCustom = $args['isCustom'];
  $items = $args['items'];

  $queryArgs = array(
    'post_type' => 'event',
    'post_status' => 'publish',
    'posts_per_page' => 2,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'date_start',
        'value' => date('Ymd'), // Date d'aujourd'hui au format YYYYMMDD
        'compare' => '>=',      // Supérieur ou égal à aujourd'hui
        'type' => 'NUMERIC',
      ),
    ),
  );


  $events_query = new WP_Query($queryArgs);
  $events = $events_query->posts; // Tableau de posts

  if ($isCustom) {
    $events = $items;
  }
?>
<div class="py-section bg-layout-main theme-dark-blue relative">
  <div class="px-container">
      <div class="flex flex-col md:flex-row @@:gap-y-[24px] justify-between items-start autoscale-children">
        <div class="flex flex-col @@:gap-y-[24px] items-start  @md/lg:max-w-[638px]">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $content, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <?php if($link): ?>
          <a href="<?= $link['url'] ?>" class="button button-primary button-flat aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $link['title'] ?></span>
          </a>
        <?php endif; ?>
      </div>
      <?php if ( $events ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[54px] @@:mt-[44px] *:md:stagger-2">
          <?php foreach ($events as $i => $post) : setup_postdata($post); ?>
            <div class="col-span-1 <?php if($i === 0): ?> md:col-start-2 <?php endif; ?> aos animate-fadeinup stagger-delay-200">
              <?php echo get_template_part('/components/event', null, array('id' => get_the_ID(), "theme" => 'blue')); ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    
  </div>
  <div class="absolute mm-sm:hidden bottom-0 left-0 z-[1]">
    <img src="<?php echo get_template_directory_uri() ?>/assets/events.svg" alt="Event Background" class="@sm:w-[354px] @md/lg:w-[480px] h-auto"/>
  </div>
</div>