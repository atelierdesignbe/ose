<?php
/**
 * Search Results Template
 */
global $adwp, $wp_query;
$search_query = get_search_query();
$found_posts  = $wp_query->found_posts;
?>
<?php get_header(); ?>


<?php get_template_part('/components/header/markup', 'header', get_field('header', 'acf-options-global-fields')); ?>


<main id="search-results">

  <!-- HERO SEARCH -->
  <section class="search-results-page-hero theme-white bg-layout-main relative overflow-hidden">
    <div class="px-container flex flex-col @@:gap-y-[20px] relative z-10 autoscale-children">
      <p class="@sm:text-[13px] @md/lg:text-[13px] @sm:tracking-[1px] @md/lg:tracking-[2px] uppercase text-dark-blue"><?= pll__('Search & Press Enter', 'atelierdesign') ?></p>
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
        <p class="paragraph-lg paragraph-primary paragraph autoscale">
          <?= $found_posts ?> <?= $found_posts > 1 ? pll__('results found', 'atelierdesign') : pll__('result found', 'atelierdesign') ?>
        </p>
      <?php endif; ?>
        <div class="grid grid-base @@:gap-x-[12px] @@:gap-y-[12px]">
          <?php while (have_posts()) : the_post(); ?>
            <div class="col-span-12 md:col-span-8">
              <a href="<?= esc_url(get_permalink()) ?>" class="search-result-item block h-full autoscale-children">
                <div class="search-result-item-inner ">
                  <div>
                    <?php
                    $post_type_obj = get_post_type_object(get_post_type());
                    $type_label = $post_type_obj ? $post_type_obj->labels->singular_name : '';
                    if ($type_label && get_post_type() !== 'page' && get_post_type() !== 'post') :
                    ?>
                      <!-- <span class="badge badge-primary badge-normal"><?= esc_html($type_label) ?></span> -->
                    <?php endif; ?>
                    <p class="search-result-item-title heading heading-md heading-primary"><?= get_the_title() ?></p>
                  </div>
                  <span class="button button-underline button-primary is-fake">
                    <span class="button-title"><?= pll__('Read more', 'atelierdesign') ?></span>
                  </span>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php
        $max_pages = $wp_query->max_num_pages;
        if ($max_pages > 1) :
          $paged = max(1, get_query_var('paged'));
          $links = paginate_links([
            'total'     => $max_pages,
            'current'   => $paged,
            'prev_text' => pll__('Prev', 'atelierdesign'),
            'next_text' => pll__('Next', 'atelierdesign'),
            'mid_size'  => 2,
            'end_size'  => 1,
            'type'      => 'array',
          ]);
        ?>
          <nav class="search-pagination" aria-label="<?= esc_attr(pll__('Search pages', 'atelierdesign')) ?>">
            <ul class="search-pagination-list">
              <?php foreach ($links as $link) : ?>
                <li class="search-pagination-item"><?= $link ?></li>
              <?php endforeach; ?>
            </ul>
          </nav>
        <?php endif; ?>

      </div>
    </section>

  <?php else : ?>
    <!-- No results -->
    <section class="theme-light-grey bg-layout-main py-section">
      <div class="px-container">
        <p class="heading heading-lg heading-primary">
          <?php if ($search_query) : ?>
            <?= sprintf(pll__('No results found for "%s"', 'atelierdesign'), esc_html($search_query)) ?>
          <?php else : ?>
            <?= pll__('Please enter a search term.', 'atelierdesign') ?>
          <?php endif; ?>
        </p>
        <p class="paragraph paragraph-primary paragraph-lg @@:mt-[24px]">
          <?= pll__('Try different keywords or browse our content.', 'atelierdesign') ?>
        </p>
      </div>
    </section>
  <?php endif; ?>

</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer'); ?>

<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
