<?php

/**
 * Template Name: Publications
 * Template Post Type: page
 */

?>
<?php global $adwp; ?>
<?php get_header(); ?>
<?php
  $fields = get_fields();

  $types = get_terms([
    'taxonomy'   => 'types', // Remplace 'type' par le nom exact de ta taxonomie
    'hide_empty' => true,
  ]);

  $themes = get_terms([
    'taxonomy'   => 'themes', // Remplace 'type' par le nom exact de ta taxonomie
    'hide_empty' => true,
  ]);

   $args = array(
    'post_type' => 'publication',
    'post_status' => 'publish',
    'posts_per_page' => -1,
  );

  $allProjects = new WP_Query($args);
  $authors = [];
  $authors_ID = [];

  foreach($allProjects->posts as $post): 
    $author = get_field('author', $post->ID);
    foreach($author as $item):
      if (!in_array($item->post_title, $authors)) {
        $authors[] = $item->post_title; 
        $authors_ID[] = $item->ID; 
      }
    endforeach;
  endforeach;
  
  wp_reset_postdata();

  $projects = get_terms([
    'taxonomy'   => 'projects', // Remplace 'type' par le nom exact de ta taxonomie
    'hide_empty' => true,
  ]);



  $args = array(
    'post_type' => 'publication',
    'post_status' => 'publish',
    'posts_per_page' => 16,
    'meta_key' => 'date_start',
    'orderby' => 'meta_value',
    'order' => 'DESC',
  );

  $publications = new WP_Query($args);

?>
<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>
<main id="publications" class="overflow-hidden relative" js-ajax="publication">
  <div class="px-container @sm:pt-[120px] @md/lg:pt-[144px] @@:pb-[78px]">
    <div class="w-full @md/lg:max-w-[945px] ">
      <div class="flex flex-col @@:gap-y-[46px] autoscale-children">
        <h1 class="heading heading-2xl heading-primary aos animate-fadeinup"><?= the_title(); ?></h1>
        <?php if($fields['content']): ?><p class="paragraph paragraph-xl paragraph-primary text-balance aos animate-fadeinup animate-delay-200"><?= $fields['content'] ?></p><?php endif; ?>
      </div>
    </div>
    
    <?php if($types || $themes ||$authors || $projects): ?>
      
      <div class="filter flex flex-col md:flex-row md:items-center flex-wrap @@:gap-[16px] @@:mt-[60px] aos animate-fadeinup relative z-[1]">
        <p class="filter-label subtitle autoscale text-dark-blue">Filter by</p>
        <!-- <div class=""></div> -->
        <div class="flex flex-wrap flex-row mm-sm:justify-between @sm:gap-y-[12px] @md/lg:gap-x-4">
          <?php if($themes): ?>
            <div class="relative mm-sm:w-[48%] md:w-auto">
              <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between filter-button" js-expand-button>
                <span class="button-title">Theme</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 left-0 translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue w-full bg-white text-dark-blue" js-ajax-filter="themes">
                  <?php foreach($themes as $theme): ?>
                    <li js-expand-item>
                      <button type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn" data-id="<?= $theme->term_id ?>" data-name="<?= $theme->name ?>">
                        <span><?= $theme->name ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>
          
          <?php if($types): ?>
            <div class="relative mm-sm:w-[48%]">
              <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between filter-button" js-expand-button>
                <span class="button-title">Type</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 right-0 md:left-0 md:right-auto translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue w-full bg-white text-dark-blue" js-ajax-filter="types">
                  <?php foreach($types as $type): ?>
                    <li js-expand-item>
                      <button type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn" data-id="<?= $type->term_id ?>" data-name="<?= $type->name ?>" >
                        <span><?= $type->name ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

          <?php if($authors): ?>
            <div class="relative mm-sm:w-[48%]">
              <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between" js-expand-button>
                <span class="button-title">Author</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 left-0 translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue bg-white text-dark-blue w-full" js-ajax-filter="authors">
                  <?php foreach($authors as $i => $author): ?>
                    <li js-expand-item>
                      <button type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn" data-id="<?= $authors_ID[$i] ?>" data-name="<?= $author ?>" >
                        <span><?= $author ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

          <?php if($projects): ?>
            <div class="relative mm-sm:w-[48%]">
              <button type="button" class="button button-primary border border-yellow flex items-center @@:px-[20px] @@:py-[22px] @@:gap-x-2 autoscale text-dark-blue w-full justify-between" js-expand-button>
                <span class="button-title">Project</span>
                <?php echo icon('chevron', '@@:w-[13px] h-auto stroke-current'); ?>
              </button>
              <div class="expand filter-expand autoscale @@:py-[4px] absolute bottom-0 right-0 md:left-0 md:right-auto  translate-y-full z-[10] mm-sm:w-[208%] md:w-[250px]" js-expand="all">
                <ul class="border border-dark-blue bg-white text-dark-blue w-full" js-ajax-filter="projects">
                  <?php foreach($projects as $project): ?>
                    <li js-expand-item>
                      <button type="button" class="@@:text-[14px] @@:leading-[20px] @@:tracking-[1px] @@:px-[20px] @@:py-[10px] w-full text-left filter-item-btn" data-id="<?= $project->term_id ?>" data-name="<?= $project->name ?>" >
                        <span><?= $project->name ?></span>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="filter-reset autoscale-children flex items-center flex-wrap @@:gap-2 relative z-[1]" js-ajax-reset></div>
      </div>

    <?php endif; ?>


    <?php if ( $publications->have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 @@:gap-[15px] @@:mt-[48px] *:md:stagger-2" js-ajax-results>
        <?php while ( $publications->have_posts() ) : $publications->the_post(); ?>
          <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
            <?php echo get_template_part('/components/publication', null, array('id' => get_the_ID())); ?>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>

    <div class="flex items-center justify-center" js-ajax-pagination>
      <?php if($publications->found_posts > $publications->post_count): ?>
        <button type="button" class="button button-primary button-flat autoscale @@:mt-[60px]">
          <span class="button-title">Load more</span>
        </button>
      <?php endif; ?>
    </div>
  </div>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[-1] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto"/>
  <?php  wp_reset_postdata(); // ← Important ! ?>
  <?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
</main>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>