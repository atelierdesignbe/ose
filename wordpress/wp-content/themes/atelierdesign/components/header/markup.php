<?php 
  $link = $args['header-contact'];
  $nav = $args['header-nav'];
?>

<header class="header" js-header>
  <div class="px-container flex items-center justify-between">
    <!--  logo  -->
    <div class="">
      <?php echo get_template_part('/components/logo', null, ['logo' => get_field('logo', 'acf-options-global-fields')]); ?>
    </div>
    <nav class="flex flex-wrap items-center">
      <button type="button" class="menu-btn btn-animation autoscale" js-menu-button>
        <span class="button-title">Menu</span>
        <span class="menu-btn-lines">
          <span class="menu-btn-line"></span>
          <span class="menu-btn-line"></span>
          <span class="menu-btn-line"></span>
        </span>
      </button>
      <div class="menu flex flex-col md:flex-row gap-x-8">
        <?php if($nav): ?>
          <ul class="flex flex-col md:flex-row">
            <?php foreach($nav as $item): ?>
              <li><a href="<?= get_permalink($item->ID); ?>" class=""><?= get_the_title($item->ID); ?></a></li>
            <?php endforeach;?>
          </ul>
        <?php endif; ?>

        <?php if($link): ?>
          <a href="<?= $link['url'] ?>" target="<?= $link['target'] ?? '_self' ?>" class="button button-border button-primary">
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
  </div>
</header>
