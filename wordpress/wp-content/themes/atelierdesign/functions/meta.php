<?php 
function render_meta_tags() {
  $title       = get_field('meta_title') . ' | '.strtoupper(get_bloginfo('name'));
  $description = get_field('meta_description');
  $og_image    = get_field('og_image');
  $theme_uri   = get_template_directory_uri();
  ?>

  <title><?php echo esc_html($title); ?></title>
  <meta name="description" content="<?php echo esc_attr($description); ?>" />

  <meta property="og:title"       content="<?php echo esc_attr($title); ?>" />
  <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
  <meta property="og:type"        content="website" />
  <meta property="og:url"         content="<?php echo esc_url(get_permalink()); ?>" />
  <?php if ($og_image) : ?>
  <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
  <?php endif; ?>

  <meta name="twitter:card"        content="summary_large_image" />
  <meta name="twitter:title"       content="<?php echo esc_attr($title); ?>" />
  <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
  <?php if ($og_image) : ?>
  <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />
  <?php endif; ?>

  <!-- Favicon -->
  <link rel="icon"             type="image/png"     href="<?php echo esc_url($theme_uri); ?>/public/favicon-96x96.png" sizes="96x96" />
  <link rel="icon"             type="image/svg+xml" href="<?php echo esc_url($theme_uri); ?>/public/favicon.svg" />
  <link rel="shortcut icon"                         href="<?php echo esc_url($theme_uri); ?>/public/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180"      href="<?php echo esc_url($theme_uri); ?>/public/apple-touch-icon.png" />
  <link rel="manifest"                              href="<?php echo esc_url($theme_uri); ?>/public/site.webmanifest" />

  <?php
}