<?php
global $adwp;
$cta = get_field('cta', 'acf-options-global-fields');
$state = $args['state'];

if ($state === 'override') $cta = $args['cta'];

if ($cta && $cta['items'] && $state !== 'disabled'):
$size = sizeof($cta['items']);
$link = $cta['link'];

if ($size == 1) $cols = 'grid-cols-1 md:grid-cols-1';
else $cols = 'grid-cols-1 md:grid-cols-2';

?>

<div class="grid <?= $cols ?> cta">
  <?php foreach($cta['items'] as $i => $item):?>
    <div class="col-span-1 bg-layout-main <?= $i == 0 ? 'theme-light-blue' : 'theme-white' ?> @sm:py-[70px] @sm:px-[30px] @md/lg:p-[100px] relative">
      <div class="relative z-[1] flex flex-col justify-between items-center text-center h-full">
        <?php $adwp->get_template_part('_wysiwyg',  array('content' => $item['content'], 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        <a href="<?= $item['link']['url'] ?>" class="button button-flat button-primary aos animate-fadeinup animate-delay-100">
          <span class="button-title">
            <?= $item['link']['title'] ?>
          </span>
        </a>
      </div>
      <?php if($i == 1): ?>
        <div class="absolute inset-0 z-[0] parallax-image-wrapper opacity-70">
            <img src="<?= get_template_directory_uri() ?>/assets/cta.jpg"  alt="" class="parallax-image object-cover w-full h-ful" />
            <div class="absolute inset-0 bg-yellow mix-blend-soft-light"></div>
            <div class="absolute inset-0 bg-yellow mix-blend-hue"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white to-transparent"></div>
          </div>
        <?php endif ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>