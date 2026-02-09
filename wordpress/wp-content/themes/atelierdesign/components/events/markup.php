<?php
  $args = array(
  'post_type' => 'event',
  'post_status' => 'publish',
  'posts_per_page' => 2,
  'meta_key' => 'date_start',
  'orderby' => 'meta_value',
  'order' => 'ASC', // Du plus proche au plus éloigné
  // 'meta_query' => array(
  //   array(
  //     'key' => 'date_start',
  //     'value' => $today,
  //     'compare' => '>=', // Date >= aujourd'hui
  //     'type' => 'DATE'
  //   )
  // )
);

  $events = new WP_Query($args);
?>
<div class="py-section bg-layout-main theme-dark-blue">
  <div class="px-container">
      <div class="flex flex-col @@:gap-y-[24px] md:flex-row justify-between items-start">
        <h2 class="heading heading-2xl heading-primary @md/lg:max-w-[638px] text-balance">Where to connect and exchange</h2>
        <a href="/events" class="button button-primary button-flat">
          <span class="button-title">See all events</span>
        </a>
      </div>
      <?php if ( $events->have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[44px]">
          <?php 
            $i = 0;
            while ( $events->have_posts() ) : $i++; $events->the_post(); ?>
            <div class="col-span-1 <?php if($i === 1): ?> md:col-start-2 <?php endif; ?>">
              <?php echo get_template_part('/components/event', null, array('id' => get_the_ID())); ?>
            </div>
          <?php endwhile?>
        </div>
      <?php endif; ?>
    
  </div>
</div>