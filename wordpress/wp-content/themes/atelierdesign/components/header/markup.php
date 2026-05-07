<?php
  $link      = $args['header-contact'];
  $nav       = $args['header-nav'];          // ACF clone → ['items' => [...]]
  $nav_items = is_array( $nav ) ? ( $nav['items'] ?? [] ) : [];
  $theme     = $args['theme'] ?? 'text-dark-blue';
  $isBlendMode = $args['isBlendMode'] ?? false;

  $current_id = get_queried_object_id();
  $ancestors  = get_post_ancestors( $current_id );
?>
<header class="header <?= $isBlendMode ? 'is-blend-mode' : '' ?>" js-header>
  <div class="container relative">
    <div class="header-wrapper flex items-center justify-between autoscale-children <?= $theme ?>">

      <!--  Logo + search desktop  -->
      <div class="flex items-center @@:gap-x-[32px]">
        <div class="logo-brand">
          <?php echo get_template_part('/components/logo', null, ['logo' => get_field('logo', 'acf-options-global-fields')]); ?>
        </div>
        <!-- Search button — desktop only -->
        <div class="search-header-btn-wrapper">
          <button
            type="button"
            class="search-header-btn hidden md:flex items-center button button-simple link-underline"
            js-search-open
            aria-label="Open search"
          >
            <?= icon('search', 'stroke-[2] overflow-visible' ); ?>
            <span class="button-title uppercase hidden lg:flex"><?= __('Search', 'atelierdesign') ?></span>
          </button>
        </div>
      </div>

      <nav class="flex flex-wrap items-center">

        <!-- Burger + search — mobile only -->
        <div class="md:hidden z-[99] relative flex items-center @@:gap-x-[12px]">
          <button
            type="button"
            class="search-mobile-btn button button-none button-primary flex items-center"
            js-search-open
            aria-label="Open search"
          >
            <?= icon('search', 'overflow-visible stroke-[2]'); ?>
          </button>
          <button type="button" class="button button-flat button-primary menu-btn btn-animation autoscale <?php if($theme === 'text-dark-blue'): ?> bg-dark-blue text-white <?php else: ?> bg-white text-dark-blue <?php endif; ?>" js-menu-button>
            <span class="button-title">Menu</span>
            <span class="menu-btn-lines">
              <span class="menu-btn-line"></span>
              <span class="menu-btn-line"></span>
              <span class="menu-btn-line"></span>
            </span>
          </button>
        </div>

        <!-- Nav panel -->
        <div class="menu autoscale-children" js-menu data-lenis-prevent>
          <div class="menu-wrapper">

            <?php if ( ! empty( $nav_items ) ) : ?>
              <ul
                class="menu__list menu-nav mm-sm:opacity-0 mm-sm:translate-y-[20px]"
                data-menu-mode="dropdown"
                js-menu-item
              >
                <?php foreach ( $nav_items as $item ) :
                  $type      = $item['type']  ?? 'link';
                  $link_data = $item['link']  ?? null;
                  $label     = $item['label'] ?? '';
                  $subItems  = is_array( $item['items'] ) ? $item['items'] : [];

                  $link_url    = is_array( $link_data ) ? ( $link_data['url']    ?? '' ) : '';
                  $link_title  = is_array( $link_data ) ? ( $link_data['title']  ?? '' ) : '';
                  $link_target = is_array( $link_data ) ? ( $link_data['target'] ?? '' ) : '';

                  $linked_id   = ! empty( $link_url ) ? url_to_postid( $link_url ) : 0;
                  $is_active   = $linked_id && ( $linked_id === $current_id );
                  $is_ancestor = $linked_id && in_array( $linked_id, $ancestors );

                  $has_submenu = ( $type === 'submenu' ) && ! empty( $subItems );
                ?>
                  <li class="menu__item">

                    <?php if ( $type === 'link' && ! empty( $link_url ) ) : ?>

                      <a
                        href="<?= esc_url( $link_url ) ?>"
                        <?= ! empty( $link_target ) ? 'target="' . esc_attr( $link_target ) . '"' : '' ?>
                        class="button button-none button-primary link-underline menu__link<?= ( $is_active || $is_ancestor ) ? ' is-active' : '' ?>"
                      >
                        <span class="button-title"><?= esc_html( $link_title ) ?></span>
                      </a>

                    <?php elseif ( $has_submenu ) : ?>

                      <button
                        class="menu__trigger button button-none button-primary<?= ( $is_active || $is_ancestor ) ? ' is-active' : '' ?>"
                        aria-expanded="false"
                        type="button"
                      >
                        <span class="menu__trigger-label">
                          <span class="button-title"><?= esc_html( $label ) ?></span>
                          <span class="menu__trigger-chevron"><?= icon( 'chevron', 'stroke-current @sm:h-[8px] @md/lg:h-[6px] w-auto'  ) ?></span>
                        </span>
                      </button>

                      <div
                        class="menu__dropdown"
                        aria-hidden="true"
                        data-depth-mode="accordeon"
                      >
                        <ul class="menu__list menu__list--sub">
                          <?php foreach ( $subItems as $sub ) :
                            $sub_link = $sub['link'] ?? null;
                            if ( ! is_array( $sub_link ) || empty( $sub_link['url'] ) ) continue;

                            $sub_url    = $sub_link['url']    ?? '';
                            $sub_title  = $sub_link['title']  ?? '';
                            $sub_target = $sub_link['target'] ?? '';
                            $sub_id     = url_to_postid( $sub_url );
                            $sub_active = $sub_id && ( $sub_id === $current_id );
                          ?>
                            <li class="menu__item">
                              <a
                                href="<?= esc_url( $sub_url ) ?>"
                                <?= ! empty( $sub_target ) ? 'target="' . esc_attr( $sub_target ) . '"' : '' ?>
                                class="button button-none button-primary menu__link<?= $sub_active ? ' is-active' : '' ?>"
                              >
                                <span class="button-title"><?= esc_html( $sub_title ) ?></span>
                              </a>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      </div>

                    <?php endif; ?>

                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <!-- CTA + langue -->
            <div class="flex flex-col @sm:gap-y-8 @md/lg:gap-y-4 @@:gap-x-4 md:flex-row items-start md:items-center mm-sm:w-full mm-sm:opacity-0 mm-sm:translate-y-[20px]" js-menu-item>
              <?php if($link): ?>
                <a href="<?= $link['url'] ?>" target="<?= $link['target'] ?? '_self' ?>" class="button button-flat md:button-outline button-primary mm-sm:w-full mm-sm:justify-center <?php if($theme === 'text-dark-blue'): ?>md:border-dark-blue <?php else: ?> border-white text-white <?php endif; ?>">
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
        </div>

      </nav>

    </div>
    <div class="header-wrapper-line z-1 <?= $theme === 'text-dark-blue' ? 'bg-dark-blue' : 'bg-white' ?>"></div>

  </div>
</header>
<?php get_template_part('/components/search/markup', 'search'); ?>
