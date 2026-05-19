<?php
$id = $args['id'];
$theme = $args['theme'] ?? '';
$year_start = get_field('year_start', $id);
$year_end   = get_field('year_end', $id);
// N'affiche year_end que s'il est strictement supérieur à year_start
$show_end = $year_end && (int) $year_end > (int) $year_start;
// Pas de end year → affiche juste l'année de début, sans tiret
// $date     = $year_start
//   ? ( $show_end ? "$year_start – $year_end" : (string) $year_start )
//   : '';
$cover = get_field('cover', $id);
$catgory = get_field('category', $id);
?>

<a href="<?= get_permalink($id) ?>" class="bg-layout-main theme-light-yellow @sm:p-[20px] @md/lg:p-[32px] flex @@:min-h-[224px] project h-full">
  <div class="flex flex-col @@:gap-y-[16px] items-start justify-between autoscale-children">
    <div class="flex flex-col @@:gap-y-[16px] items-start ">
      <div class="flex flex-row @@:gap-2 items-center ">
        <?php if($year_start): ?><span class="badge badge-primary badge-outlined"><?= $year_start ?></span><?php endif; ?>
        <?php if($show_end): ?>
          <span><?= icon('chevron', $theme === 'blue' ? 'stroke-white @@:h-[8px] w-auto -rotate-90' : 'stroke-dark-blue @@:h-[8px] w-auto -rotate-90', true); ?></span>
          <span class="badge badge-primary badge-outlined"><?= $year_end ?></span>
        <?php endif; ?>
      </div>
      <p class="heading heading-md heading-primary"><?= get_the_title($id); ?> </p>
    </div>
    <span class="button button-underline is-fake"><span class="button-title">Read more</span></span>
  </div>
</a>