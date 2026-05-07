<?php
global $adwp;

/** 
 * Create content here
 */

if($args['label-status'] === 'default') $args['label'] = get_the_title();

ob_start(); ?>
<div class="hero-content-wrapper">
  <div class="hero-content">
    <?= $args['beforeContent']; ?>
    <?php if($args['label'] && !empty($args['label']) && $args['label-status'] !== 'disabled'): ?>
      <p class="subtitle paragraph-primary aos animate-fadeinup"><?= $args['label'] ?></p>
    <?php endif; ?>
    <?php if($args['title']): ?>
      <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup "><?= $args['title'] ?></h1>
      <?php endif; ?>
      <?= $args['beforeDescription']; ?>

    <?php if($args['content']): ?>
      <p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200"><?= $args['content'] ?></p>
    <?php endif; ?>
    <?= $args['afterContent']; ?>

  </div>
</div>

<?php $args['contentHTML'] = ob_get_clean(); ?>

<?php if($args['cover-status'] === 'none' || !$args['cover']): ?>
  <?php get_template_part('/components/hero/hero-none', null, $args) ?>
<?php elseif($args['cover-status'] === 'default'): ?>
  <?php get_template_part('/components/hero/hero-fullsize', null, $args) ?>
<?php elseif($args['cover-status'] === 'fill'): ?>
  <?php get_template_part('/components/hero/hero-fill', null, $args) ?>
<?php elseif($args['cover-status'] === 'fit'): ?>
  <?php get_template_part('/components/hero/hero-fit', null, $args) ?>
<?php endif; ?>