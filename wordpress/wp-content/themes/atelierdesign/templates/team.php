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
  <?php  $adwp->render_flexible_layout($fields['flexible_content_before']['flexible-layout']); ?>
  <?php
  $member_types = get_terms([
    'taxonomy'   => 'member_type',
    'hide_empty' => true,
    'orderby'    => 'term_order',
    'order'      => 'ASC',
  ]);
  ?>

  <?php if ( ! empty( $member_types ) && ! is_wp_error( $member_types ) ) :
    foreach ( $member_types as $type ) :

      $members_query = new WP_Query([
        'post_type'      => 'author',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
        'tax_query'      => [[
          'taxonomy' => 'member_type',
          'field'    => 'term_id',
          'terms'    => $type->term_id,
        ]],
      ]);

      if ( ! $members_query->have_posts() ) {
        wp_reset_postdata();
        continue;
      }
  ?>

  <section class="py-section theme-white bg-layout-main">
    <div class="container">
      <div class="flex flex-col @sm:gap-y-[40px] @md/lg:gap-y-[48px]">

        <h2 class="heading heading-xl heading-primary aos animate-fadeinup"><?= esc_html( $type->name ) ?></h2>

        <div class="grid grid-cols-1 md:grid-cols-4 @sm:gap-y-[8px] @md/lg:gap-y-[40px] @md/lg:gap-x-[12px]">
          <?php while ( $members_query->have_posts() ) : $members_query->the_post(); ?>
            <div class="col-span-1 aos animate-fadeinup stagger-delay-200">
              <?php get_template_part('/components/member', null, ['id' => get_the_ID()]); ?>
            </div>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>

      </div>
    </div>
  </section>

  <?php endforeach; endif; ?>

  <?php  $adwp->render_flexible_layout($fields['flexible_content']['flexible-layout']); ?>


</main>

<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
