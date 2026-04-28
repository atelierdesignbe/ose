<?php
/**
 * Single Member Template (CPT: member)
 */
global $adwp;
$fields      = get_fields();
$role        = $fields['role'] ?? '';
$bio         = $fields['bio'] ?? '';
$email       = $fields['email'] ?? '';
$linkedin    = $fields['linkedin'] ?? '';
$twitter     = $fields['twitter'] ?? '';
$show_pubs   = $fields['show_publications'] ?? false;
$thumbnail_id = get_post_thumbnail_id();
?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [
  ...get_field('header', 'acf-options-global-fields'),
  'theme' => 'text-dark-blue',
]); ?>

<main id="single-member">

  <!-- HERO MEMBER -->
  <section class="theme-white bg-layout-main">
    <div class="px-container">
      <div class="grid grid-base @@:gap-y-[40px] @md/lg:pt-[120px] @sm:pt-[100px] @md/lg:pb-[80px] @sm:pb-[60px]">

        <!-- Photo -->
        <div class="col-span-12 md:col-span-8 lg:col-span-6">
          <div class="member-card-image">
            <?php if ($thumbnail_id) : ?>
              <?php echo wp_get_attachment_image($thumbnail_id, 'medium', false, ['class' => 'w-full h-full object-cover image-shadow']); ?>
            <?php else : ?>
              <div class="w-full aspect-[3/4] bg-light-blue"></div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Info -->
        <div class="col-span-12 md:col-span-14 lg:col-span-16 md:col-start-10 lg:col-start-9 flex flex-col justify-center @@:gap-y-[32px] autoscale-children">

          <!-- Breadcrumb / retour -->
          <a href="javascript:history.back()" class="button button-none button-primary flex items-center @@:gap-x-[8px] w-fit">
            <svg viewBox="0 0 47 16" xmlns="http://www.w3.org/2000/svg" class="@@:w-[32px] rotate-180 fill-current">
              <path d="M46.7071 8.70711C47.0976 8.31658 47.0976 7.68342 46.7071 7.29289L40.3431 0.928932C39.9526 0.538408 39.3195 0.538408 38.9289 0.928932C38.5384 1.31946 38.5384 1.95262 38.9289 2.34315L44.5858 8L38.9289 13.6569C38.5384 14.0474 38.5384 14.6805 38.9289 15.0711C39.3195 15.4616 39.9526 15.4616 40.3431 15.0711L46.7071 8.70711ZM0 8V9H46V8V7H0V8Z"/>
            </svg>
            <span class="button-title uppercase">Back</span>
          </a>

          <!-- Title + role -->
          <div class="flex flex-col @@:gap-y-[8px]">
            <?php if ($role) : ?>
              <span class="subtitle paragraph-primary opacity-60"><?= esc_html($role) ?></span>
            <?php endif; ?>
            <h1 class="heading heading-primary @sm:text-[40px] @md/lg:text-[56px] font-serif font-light autoscale">
              <?= esc_html(get_the_title()) ?>
            </h1>
          </div>

          <!-- Bio -->
          <?php if ($bio) : ?>
            <p class="paragraph paragraph-primary paragraph-lg"><?= esc_html($bio) ?></p>
          <?php endif; ?>

          <!-- Contact & Social -->
          <?php if ($email || $linkedin || $twitter) : ?>
            <div class="flex flex-wrap items-center @@:gap-[16px]">
              <?php if ($email) : ?>
                <a href="mailto:<?= esc_attr($email) ?>" class="button button-outline button-primary">
                  <span class="button-title"><?= esc_html($email) ?></span>
                </a>
              <?php endif; ?>
              <?php if ($linkedin) : ?>
                <a href="<?= esc_url($linkedin) ?>" target="_blank" rel="noopener noreferrer" class="button button-none button-primary" aria-label="LinkedIn">
                  <?= icon('linkedin', '@@:size-[20px]') ?>
                </a>
              <?php endif; ?>
              <?php if ($twitter) : ?>
                <a href="<?= esc_url($twitter) ?>" target="_blank" rel="noopener noreferrer" class="button button-none button-primary" aria-label="Twitter / X">
                  <?= icon('twitter', '@@:size-[20px]') ?>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </section>

  <!-- RELATED PUBLICATIONS -->
  <?php if ($show_pubs) : ?>
    <?php
    $related_publications = get_posts([
      'post_type'      => 'publication',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
      'meta_query'     => [
        [
          'key'     => 'author',
          'value'   => '"' . get_the_ID() . '"',
          'compare' => 'LIKE',
        ],
      ],
    ]);
    ?>
    <?php if (!empty($related_publications)) : ?>
      <section class="theme-light-blue bg-layout-main py-section">
        <div class="px-container">
          <h2 class="heading heading-lg heading-primary @@:mb-[48px]">Publications</h2>
          <div class="flex flex-col @@:gap-y-[2px]">
            <?php foreach ($related_publications as $pub) : ?>
              <?php get_template_part('/components/publication', null, ['id' => $pub->ID]); ?>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
  <?php endif; ?>

</main>

<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>
