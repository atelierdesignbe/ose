<?php 
/**
 * Template Name: Contact
 * Template Post Type: page
 */
global $adwp; ?>
<?php
// cover-status
?>
<?php $fields = get_fields(); ?>
<?php get_header(); ?>
<?php get_template_part('/components/header/markup', 'header', [...get_field('header', 'acf-options-global-fields'), 'theme' => $fields['hero']['cover-status'] === 'default' ? 'text-white' : 'text-dark-blue']); ?>
<main id="index">
  <?php get_template_part('/components/hero/markup', 'hero', $fields['hero']); ?>
  <div class="theme-white bg-layout-main py-section">
    <div class="px-container">
      <div class="grid grid-base @@:gap-y-lg">
        <div class="col-span-12 md:col-span-7">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $fields['content'], 'inside' => true, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="col-span-12 md:col-span-15 md:col-start-10">
          <?php if($fields['form_id']): ?>
            <div class="aos animate-fadeinup animate-delay-200">
              <?php echo do_shortcode("[formidable id='".$fields['form_id']."']"); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php get_template_part('/components/cta-footer/markup', 'cta-footer', ['state' => $fields['cta_status'], 'cta' => $fields['cta']]); ?>
<?php get_template_part('/components/footer/markup', 'footer', get_field('footer', 'acf-options-global-fields')); ?>
<?php get_footer(); ?>