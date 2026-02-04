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
    <div class="col-span-1 <?= $i ? 'bg-gray-100' : 'bg-gray-200' ?> @sm:py-[70px] @sm:px-[30px] @md/lg:p-[100px] flex flex-col justify-between items-center text-center">
      <?php $adwp->get_template_part('_wysiwyg',  array('content' => $item['content'], 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
      <a href="<?= $item['link']['url'] ?>" class="button button-flat button-primary aos animate-fadeinup animate-delay-100">
        <span class="button-title">
          <?= $item['link']['title'] ?>
        </span>
      </a>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>