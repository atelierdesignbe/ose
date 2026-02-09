<?php
$id = $args['id'];
$date = get_field('date_start', $id);
$cover = get_field('cover', $id);
$catgory = get_field('category', $id);
?>

<a href="<?= get_permalink($id) ?>" class="bg-layout-main theme-light-yellow @sm:p-[20px] @md/lg:p-[32px] flex @md/lg:min-h-[224px]">
  <div class="flex flex-col @@:gap-y-[16px] items-start justify-between">
    <div class="flex flex-col @@:gap-y-[16px]">
      <?php if($date): ?><span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
      <p class="heading heading-md heading-primary"><?= get_the_title($id); ?> </p>
    </div>
    <span class="button button-underline button-primary">Read more</span>
  </div>
</a>