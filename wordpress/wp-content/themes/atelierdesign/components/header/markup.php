<?php 
  $link = $args['header-contact'];
  $nav = $args['header-nav'];
?>

<header class="header" js-header>
  <div class="px-container">
    <div class="header-wrapper flex items-center justify-between autoscale-children text-dark-blue theme-white">
      <!--  logo  -->
      <div class="logo-brand">
        <?php echo get_template_part('/components/logo', null, ['logo' => get_field('logo', 'acf-options-global-fields')]); ?>
      </div>
      <nav class="flex flex-wrap items-center">
        <button type="button" class="button button-flat button-primary menu-btn btn-animation" js-menu-button>
          <span class="button-title">Menu</span>
          <span class="menu-btn-lines">
            <span class="menu-btn-line"></span>
            <span class="menu-btn-line"></span>
            <span class="menu-btn-line"></span>
          </span>
        </button>
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

          <?php if($link): ?>
            <a href="<?= $link['url'] ?>" target="<?= $link['target'] ?? '_self' ?>" class="button button-outline button-secondary">
              <span class="button-title"><?= $link['title'] ?></span>
            </a>
          <?php endif; ?>

          <?php
            if (function_exists('pll_current_language')) {
                display_lang();
            }
          ?>
        </div>
      </nav>
      <div class="header-wrapper-line"></div>
    </div>
  </div>
</header>
