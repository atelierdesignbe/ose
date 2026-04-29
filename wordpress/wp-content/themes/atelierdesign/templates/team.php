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
]); ?>

<main id="team">

  <?php get_template_part('/components/hero/markup', 'hero', $fields['hero']); ?>

  <!-- Members grid -->
  <section class="theme-white bg-layout-main py-section">
    <div class="px-container">
      <?php
      $members_query = new WP_Query([
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
      ]);
      ?>

      <?php if ($members_query->have_posts()) : ?>
        <div class="grid grid-base @@:gap-x-[24px] @@:gap-y-[48px]">
          <?php while ($members_query->have_posts()) : $members_query->the_post(); ?>
            <div class="col-span-6 sm:col-span-4 md:col-span-6 lg:col-span-4">
              <?php get_template_part('/components/member', null, ['id' => get_the_ID()]); ?>
            </div>
          <?php endwhile; wp_reset_postdata(); ?>
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
