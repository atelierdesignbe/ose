<?php
  $args = array(
  'post_type' => 'project',
  'post_status' => 'publish',
  'posts_per_page' => 3,
);

  $events = new WP_Query($args);
?>
<div class="py-section bg-layout-main theme-yellow">
  <div class="px-container">
      <div class="flex flex-col md:flex-row @@:gap-y-[24px] justify-between items-start">
        <div class="flex flex-col @@:gap-y-[24px] items-start  @md/lg:max-w-[638px]">
          <h2 class="heading heading-2xl heading-primary text-balance">From evidence to impact</h2>
          <p class="paragraph paragraph-primary paragraph-md">Our projects are collaborative and often transnational. They aim to deliver concrete outcomes while building bridges between research and policy implementation. Discover the initiatives shaping tomorrow’s public action.</p>
        </div>
        <a href="/projects" class="button button-primary button-flat">
          <span class="button-title">See all projects</span>
        </a>
      </div>
      <?php if ( $events->have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-3 @@:gap-[15px] @@:mt-[44px]">
          <?php 
            $i = 0;
            while ( $events->have_posts() ) : $i++; $events->the_post(); ?>
            <div class="col-span-1">
              <?php echo get_template_part('/components/project', null, array('id' => get_the_ID())); ?>
            </div>
          <?php endwhile?>
        </div>
      <?php endif; ?>
    
  </div>
</div>