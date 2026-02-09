<?php
$id = $args['id'];
$date_start = get_field('date_start', $id);
$date_end = get_field('date_end', $id);
$cover = get_field('cover', $id);
$catgory = get_field('category', $id);
?>

<a href="<?= get_permalink($id) ?>" class=" @@:p-[20px]">
  <div class="flex flex-col items-start @@:gap-y-[16px] @sm:py-[20px] @md/lg:py-[32px] justify-betwwen  border-t border-yellow">
    <div class="flex flex-col items-start @@:gap-y-[16px]">
      <div class="flex flex-row @@:gap-x-2 items-center">
        <?php if($date_start): ?><span class="badge badge-primary badge-outlined"><?= $date_start ?></span><?php endif; ?>
        <?php if($date_end): ?><span><?= icon('chevron', 'stroke-white @@:h-[8px] w-auto -rotate-90'); ?></span><span class="badge badge-primary badge-outlined"><?= $date_end ?></span><?php endif; ?>
      </div>
      <p class="heading heading-md heading-primary"><?= get_the_title($id); ?> </p>
    </div>
    <span class="button button-underline button-primary"><span class="button-title">Read more</span></span>
  </div>
</a>