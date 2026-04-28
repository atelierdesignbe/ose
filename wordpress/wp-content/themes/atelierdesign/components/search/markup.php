<?php
$home_url = home_url('/');
?>
<div class="search-modal" js-search-modal role="dialog" aria-modal="true" aria-label="<?= esc_attr__('Search', 'atelierdesign') ?>">
  <div class="search-modal-inner container relative h-full flex flex-col justify-center">
    <button
      type="button"
      class="search-modal-close"
      js-search-close
      aria-label="<?= esc_attr__('Close search', 'atelierdesign') ?>"
    >
      <?= icon('close', 'text-white'); ?>
    </button>
    <div class="flex flex-col justify-center @@:gap-y-[20px]">
      <p class="search-modal-label button-title"><?= pll__('Search &amp; Press Enter', 'atelierdesign'); ?></p>
      <form
        role="search"
        method="get"
        action="<?= esc_url($home_url) ?>"
        class="search-modal-form"
      >
        <input
          type="search"
          name="s"
          class="heading heading-2xl"
          placeholder="<?= pll__('What are you looking for?', 'atelierdesign') ?>"
          value="<?= esc_attr(get_search_query()) ?>"
          autocomplete="off"
          aria-label="<?= esc_attr__('Search', 'atelierdesign') ?>"
        />
        <button type="submit" class="appearance-none"><?= icon('search', 'text-purple'); ?></button>
      </form>
    </div>
  </div>
</div>
