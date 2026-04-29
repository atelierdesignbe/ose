<?php
/**
 * Search Results Template
 */
global $adwp;
$search_query = get_search_query();
$found_posts  = $wp_query->found_posts;
?>
<?php get_header(); ?>


<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>


<main id="search-results">

  <!-- HERO SEARCH -->
  <section class="search-results-page-hero theme-white bg-layout-main relative overflow-hidden">
    <div class="px-container flex flex-col @@:gap-y-[20px] relative z-10 autoscale-children">
      <p class="button-title">Search &amp; Press Enter</p>
      <form role="search" method="get" action="<?= esc_url(home_url('/')) ?>" class="search-results-page-form">
        <textarea
          name="s"
          class="heading heading-2xl heading-primary"
          placeholder="<?= pll__('What are you looking for?', 'atelierdesign') ?>"
          autocomplete="off"
          rows="1"
          aria-label="<?= esc_attr__('Search', 'atelierdesign') ?>"
          js-search-textarea
        ><?= esc_textarea($search_query) ?></textarea>
        <button type="submit" class="appearance-none"><?= icon('search', 'text-purple'); ?></button>
      </form>
    </div>
    <img src="<?= get_template_directory_uri() ?>/assets/gradient.jpg" class="absolute top-0 right-0 z-[0] translate-x-[20%] md:translate-x-[40%] @sm:h-[770px] @md/lg:h-[800px] w-auto mm-sm:hidden"/>

  </section>

  <!-- RESULTS GRID -->
  <?php if (have_posts()) : ?>
    <section class="theme-light-grey bg-layout-main py-section ">
   
      <div class="px-container flex flex-col @sm:gap-y-[24px] @md/lg:gap-y-[40px]">
      <?php if ($search_query) : ?>
        <p class="paragraph-lg paragraph-primary paragraph">
          <?= $found_posts ?> <?= $found_posts > 1 ? 'results found' : 'result found' ?>
        </p>
      <?php endif; ?>
        <div class="grid grid-base @@:gap-x-[12px] @@:gap-y-[12px]">
          <?php while (have_posts()) : the_post(); ?>
            <div class="col-span-12 md:col-span-8">
              <a href="<?= esc_url(get_permalink()) ?>" class="search-result-item block h-full">
                <div class="search-result-item-inner ">
                  <div>
                    <?php
                    $post_type_obj = get_post_type_object(get_post_type());
                    $type_label = $post_type_obj ? $post_type_obj->labels->singular_name : '';
                    if ($type_label && get_post_type() !== 'page' && get_post_type() !== 'post') :
                    ?>
                      <span class="badge badge-primary badge-outlined"><?= esc_html($type_label) ?></span>
                    <?php endif; ?>
                    <p class="search-result-item-title heading heading-md heading-primary"><?= get_the_title() ?></p>
                  </div>
                  <span class="button button-underline button-primary is-fake">
                    <span class="button-title">Read more</span>
                  </span>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
      
      </div>
    </section>

  <?php else : ?>
    <!-- No results -->
    <section class="theme-light-grey bg-layout-main py-section">
      <div class="px-container">
        <p class="heading heading-lg heading-primary">
          <?php if ($search_query) : ?>
            No results found for &ldquo;<?= esc_html($search_query) ?>&rdquo;
          <?php else : ?>
            Please enter a search term.
          <?php endif; ?>
        </p>
        <p class="paragraph paragraph-primary paragraph-lg @@:mt-[24px]">
          Try different keywords or browse our content.
        </p>
      </div>
    </section>
  <?php endif; ?>

</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer'); ?>

<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
