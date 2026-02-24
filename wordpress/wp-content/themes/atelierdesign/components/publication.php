<?php
$id = $args['id'];
$date = get_field('date_start', $id);
$cover = get_field('cover', $id);
$catgory = get_field('category', $id);
$isExternal = get_field('is-external', $id);
$externalLink = get_field('external-link', $id);

$theme = $args['theme'] ?? 'theme-light-blue';

if(!$cover) $cover = get_field('publication-placeholder', 'acf-options-global-fields');

?>

<a href="<?= $isExternal ? $externalLink : get_permalink($id) ?>" class="bg-layout-main <?= $theme ?> @@:p-[20px] flex @md/lg:min-h-[220px] h-full overflow-hidden publication" <?php if($isExternal): ?> target="_blank" <?php endif; ?>>
  <div class="flex-none @sm:w-[88px] @md/lg:w-[158px]">
    <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => 'object-contain w-full image-shadow']) ?>
  </div>
  <div class="flex flex-col items-start flex-auto <?= $isExternal ? '@sm:gap-y-[24px] @md/lg:gap-y-[16px]' : '@@:gap-y-[16px]' ?> @@:gap-y-[16px] @sm:px-[20px] @md/lg:px-[32px] @md/lg:py-[18px] justify-between autoscale-children">
    <div class="flex flex-col items-start @@:gap-y-[16px]">
      <div class="flex flex-wrap flex-row @@:gap-2 items-center">
        <?php if($date): ?><span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
        <?php if($theme === 'theme-light-blue'): ?>
          <?php if($catgory): ?>
            <span class="badge badge-primary badge-filled bg-dark-blue border-dark-blue text-white">In depth</span>
          <?php else: ?>
            <span class="badge badge-primary badge-filled">Summary</span>
          <?php endif; ?>
        <?php else:  ?>
          <?php if($catgory): ?>
            <span class="badge badge-primary badge-filled bg-dark-blue border-dark-blue text-white">In depth</span>
          <?php else: ?>
            <span class="badge badge-primary badge-filled bg-light-blue border-light-blue">Summary</span>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <p class="heading heading-md heading-primary"><?= get_the_title($id); ?> </p>
    </div>
    <span class="button button-underline button-primary is-fake"><span class="button-title">Read more</span></span>
    <?php if($isExternal): ?>
      <span class="bg-yellow text-dark-blue @@:size-[44px] rounded-full flex items-center justify-center absolute @sm:bottom-[-9px] @md/lg:bottom-[9px] right-0">
        <?= icon('external', '@@:size-[24px]'); ?>
      </span>
    <?php endif; ?>
  </div>
</a>