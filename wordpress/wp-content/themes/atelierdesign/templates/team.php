<?php
/**
 * Template Name: Team
 * Template Post Type: page
 */
global $adwp;
$fields = get_fields();
$cover_status = $fields['hero']['cover-status'] ?? 'default';
?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [
  ...get_field('header', 'acf-options-global-fields'),
  'theme' => $cover_status === 'default' ? 'text-white' : 'text-dark-blue',
  'isBlendMode' => $fields['hero']['cover-status'] === 'fill',
]); ?>

<main id="team">

  <?php 
      $fields['hero']['social'] = true;

  get_template_part('/components/hero/markup', 'hero', $fields['hero']); ?>

  <?php
  // Filtre actif depuis l'URL (ex: /team/administrative-team/)
  $active_filter_slug = get_query_var('member_type_filter', '');
  ?>
  <?php if ($active_filter_slug) : ?>
    <script>window._teamFilter = <?= json_encode($active_filter_slug) ?>;</script>
  <?php endif; ?>

  <!-- Members grid -->
  <section class="theme-white bg-layout-main py-section" js-team-filter>
    <div class="px-container">

      <?php
      $member_types = get_terms([
        'taxonomy'   => 'member_type',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
        
      ]);

      $members_query = new WP_Query([
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
      ]);
      ?>

      <div class="flex flex-col @sm:gap-[24px] @md/lg:gap-[24px] md:flex-row md:items-start justify-between @sm:mb-[32px] @md/lg:mb-[48px]">
        <p class="heading heading-2xl heading-primary autoscale" js-team-title><?= pll__('All members', 'atelierdesign') ?></p>

        <?php if (!empty($member_types) && !is_wp_error($member_types)) : ?>
          <!-- Filter bar -->
          <div class="flex items-center @sm:gap-x-[12px] @md/lg:gap-x-[20px] flex-shrink-0 autoscale-children">
            <p class="subtitle"><?= pll__('Filter by', 'atelierdesign') ?></p>
            <div class="relative">
              <button
                class="team-filter-btn-trigger button button-outline button-primary flex items-center @sm:px-[20px @sm:py-[22px] @md/lg:px-[20px @md/lg:py-[22px] rounded-none"
                js-expand-button
              >
                <span class="button-title" js-team-filter-label><?= pll__('Type of Member', 'atelierdesign') ?></span>
                <span class="team-filter-chevron flex items-center"><?= icon('chevron', '@@:w-[13px] h-auto stroke-current') ?></span>
              </button>
              <div
                class="team-filter-dropdown absolute right-0 z-[50]"
                js-expand
                style="display:none"
              >
                <ul class="team-filter-list flex flex-col">
                  <li>
                    <button
                      class="team-filter-item block w-full text-left is-active"
                      js-team-filter-btn
                      data-filter="all"
                      data-label="<?= esc_attr(pll__('Type of Member', 'atelierdesign')) ?>"
                      data-title="<?= esc_attr(pll__('All members', 'atelierdesign')) ?>"
                    >
                      <?= pll__('All', 'atelierdesign') ?>
                    </button>
                  </li>
                  <?php foreach ($member_types as $type) : ?>
                    <li>
                      <button
                        class="team-filter-item block w-full text-left"
                        js-team-filter-btn
                        data-filter="<?= esc_attr($type->slug) ?>"
                        data-label="<?= esc_attr($type->name) ?>"
                        data-title="<?= esc_attr($type->name) ?>"
                      >
                        <?= esc_html($type->name) ?>
                      </button>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
     

      <?php if ($members_query->have_posts()) : ?>
        <div class="grid grid-base @sm:gap-y-[8px] @md/lg:gap-y-[40px] @md/lg:gap-x-[12px]">
          <?php while ($members_query->have_posts()) : $members_query->the_post(); ?>
            <?php
            $member_terms  = wp_get_object_terms(get_the_ID(), 'member_type');
            $type_slugs    = (!empty($member_terms) && !is_wp_error($member_terms))
                               ? array_map(fn($t) => $t->slug, $member_terms)
                               : [];
            ?>
            <div
              class="col-span-12 md:col-span-6"
              js-team-member
              data-types="<?= esc_attr(json_encode($type_slugs)) ?>"
            >
              <?php get_template_part('/components/member', null, ['id' => get_the_ID()]); ?>
            </div>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Load more -->
        <div class="team-load-more-wrapper" js-team-load-more-wrapper>
          <button class="button button-outline button-primary team-load-more-btn" js-team-load-more>
            <span class="button-title"><?= pll__('Load more', 'atelierdesign') ?></span>
          </button>
        </div>

      <?php else : ?>
        <p class="paragraph paragraph-primary paragraph-lg">No members found.</p>
      <?php endif; ?>

    </div>
  </section>

</main>

<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
