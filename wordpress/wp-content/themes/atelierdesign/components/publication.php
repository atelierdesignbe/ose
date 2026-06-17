<?php
$id = $args['id'];
$date = get_field('date_start', $id);
$cover = get_field('cover', $id);
$catgory = get_field('category', $id);
$isExternal = get_field('is-external', $id);
$externalLink = get_field('external-link', $id);

$theme = $args['theme'] ?? 'theme-light-blue';
$types = get_the_terms( $id, 'types' );


$authors   = get_field('author', $id) ?: [];
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
        <?php if($types): ?>
          <?php foreach($types as $type): ?>
            <span class="badge badge-primary badge-filled">
              <?= $type->name ?>
          </span>            
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
     
      <p class="heading heading-md heading-primary "><?= get_the_title($id); ?> </p>
      <?php if ($authors) : ?>
        <ul class="flex flex-wrap items-center @sm:gap-x-0 @md/lg:gap-x-0 @sm:gap-y-[4px] @md/lg:gap-y-[4px] autoscale-children">
          <?php foreach ($authors as $i => $author) :
              $is_member   = $author->post_type === 'author';

              $lastname = get_field('lastname', $author->ID);
              $firstname = get_field('firstname', $author->ID);
              $authorTitle = explode(' ',$author->post_title);
  
              $display_name = $lastname && $firstname ? $lastname . ' ' .strtoupper(substr($firstname, 0, 1)).'.' : $authorTitle[1] . ' ' .strtoupper(substr($authorTitle[0], 0, 1)) .'.';
              if (!$is_member) $display_name = $author->post_title;
            ?>
            <li class="flex items-center">
              <span class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]"><?= esc_html($display_name) ?></span>
            </li>
            <?php if ($i < count($authors) - 1) : ?>
              <li class="@@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] flex items-center"><span>,&nbsp;</span></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
    <div class="flex flex-row w-full justify-between item-center">
      <span class="button button-underline is-fake flex self-center"><span class="button-title">Read more</span></span>
      <?php if($isExternal): ?>
        <span class="bg-yellow text-dark-blue @sm:size-[44px]  @md/lg:size-[44px] rounded-full flex items-center justify-center">
          <?= icon('external', '@@:size-[24px]'); ?>
        </span>
      <?php endif; ?>
    </div>
  </div>
</a>