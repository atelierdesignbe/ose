<?php 
  $link = $args['header-contact'];
  $nav = $args['header-nav'];
  $theme = $args['theme'] ?? 'text-dark-blue';
?>

<header class="header" js-header>
  <div class="px-container">
    <div class="header-wrapper flex items-center justify-between autoscale-children <?= $theme ?>">
      <!--  logo  -->
      <div class="logo-brand">
        <?php echo get_template_part('/components/logo', null, ['logo' => get_field('logo', 'acf-options-global-fields')]); ?>
      </div>
      <nav class="flex flex-wrap items-center">
        <div class="md:hidden">
          <button type="button" class="button button-flat button-primary menu-btn btn-animation <?php if($theme === 'text-dark-blue'): ?> bg-dark-blue text-white <?php else: ?> bg-white text-dark-blue <?php endif; ?>" js-menu-button>
            <span class="button-title">Menu</span>
            <span class="menu-btn-lines">
              <span class="menu-btn-line"></span>
              <span class="menu-btn-line"></span>
              <span class="menu-btn-line"></span>
            </span>
          </button>
        </div>
        <div class="menu ">
          <?php if($nav): ?>
            <ul class="menu-nav">
              <?php foreach($nav as $item): ?>
                <li>
                  <a href="<?= get_permalink($item->ID); ?>" class="button button-none">
                    <span class="button-title"><?= get_the_title($item->ID); ?></span>
                  </a>
                </li>
              <?php endforeach;?>
            </ul>
          <?php endif; ?>
          <div class="flex flex-col @@:gap-y-4 @@:gap-x-4 md:flex-row items-center">
            <?php if($link): ?>
              <a href="<?= $link['url'] ?>" target="<?= $link['target'] ?? '_self' ?>" class="button button-outline button-primary <?php if($theme === 'text-dark-blue'): ?>border-dark-blue<?php endif; ?>">
                <span class="button-title"><?= $link['title'] ?></span>
              </a>
            <?php endif; ?>

            <?php
              if (function_exists('pll_current_language')) {
                  display_lang();
              }
            ?>
          </div>
        </div>
      </nav>
      <div class="header-wrapper-line <?= $theme === 'text-dark-blue' ? 'bg-dark-blue' : 'bg-white' ?> "></div>
    </div>
  </div>
</header>
