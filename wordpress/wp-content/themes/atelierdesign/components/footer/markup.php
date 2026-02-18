<?php
$contact = $args['contact'];
$menu = $args['menu'];
$newsletter = $args['newsletter'];
$privacyNav = $args['bottom-nav'];
?>
<footer class="footer @sm:pt-[50px] md:pt-0 theme-light-grey bg-layout-main relative overflow-hidden">
  <div class="footer-top relative z-[1]">
    <div class="px-container grid grid-base">
      <div class="flex flex-col @@:gap-y-[32px] col-span-12 md:col-span-15 @md/lg:pt-[64px] @md/lg:pb-[180px] md:flex-row md:justify-between @md/lg:gap-x-[50px]">
        <div class="logo-brand aos animate-fadeinup flex-none text-dark-blue">
          <?php echo get_template_part('/components/logo', null, ['logo' => get_field('logo', 'acf-options-global-fields')]); ?>
        </div>
        <!-- ADDR -->
        <div class="contact flex flex-col @@:gap-y-[24px] items-start @md/lg:pr-[6.66%] @md/lg:w-[438px]">
          <?php if($contact['link']): ?>
            <a href="<?= $contact['link']['url'] ?>" target="<?= $contact['url']['target'] ?? '_self' ?>" class="heading heading-2xl heading-primary aos animate-fadeinup animate-delay-100 autoscale"><?= $contact['link']['title'] ?></a>
          <?php endif; ?>
          <?php if($contact['title']): ?><p class="subtitle mm-sm:hidden @md/lg:mt-[32px] aos animate-fadeinup animate-delay-200 text-dark-blue"><?= $contact['title'] ?></p><?php endif;?>
          <?php if($contact['addr']): ?>
            <a href="#" class="heading-lg heading-primary heading aos animate-fadeinup animate-delay-200 autoscale ">
              <?= $contact['addr'] ?>
            </a>
          <?php endif; ?>
          <div class="contact-link flex flex-col items-start aos animate-fadeinup animate-delay-300 @@:gap-y-2">
            <?php if($contact['email']): ?>
              <a href="mailto:<?= $contact['email'] ?>" target="_blank" rel="noopener noreferer" class="heading-lg heading heading-primary autoscale"><?= $contact['email'] ?></a>
            <?php endif; ?>
            <?php if($contact['phone']): ?>
              <a href="tel:<?= $contact['phone'] ?>" target="_blank" rel="noopener noreferer" class="heading-lg heading heading-primary autoscale"><?= $contact['phone'] ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- MENU -->
      <div class="flex flex-col @@:gap-y-[50px] mm-sm:border-t border-gray-200 @sm:pt-[50px] @md/lg:pt-[64px] @md/lg:pb-[42px] @sm:mt-[50px] md:mt-0 col-span-12 md:col-span-9 @md/lg:pl-[11.11%] md:border-l <?php if($newsletter && class_exists('FrmForm')): ?> justify-between <?php else: ?> justify-end <?php endif; ?>">
        <!-- NEWSLETTER -->
        <?php if($newsletter && class_exists('FrmForm')): ?>
          <div class="newsletter aos animate-fadeinup">
            <?php do_shortcode("[formidable id='".$newsletter."']"); ?>
          </div>
        <?php endif; ?>
        <div class="flex flex-col md:flex-row @@:gap-[50px] md:items-end md:justify-between">
          <?php if($menu && sizeof($menu) > 0): ?>
            <div class="footer-menu flex-none flex flex-col @@:gap-y-[24px]">
              <p class="subtitle autoscale aos animate-fadeinup text-dark-blue">Ose</p>
              <ul class="flex flex-col @@:gap-y-[24px] autoscale-children aos animate-fadeinup animate-delay-200">
                <?php foreach($menu as $item): ?>
                  <li class="leading-none">
                    <a href="<?= get_permalink($item->ID) ?>" class="paragraph paragraph-md paragraph-primary leading-none link-underline"><?= get_the_title($item->ID) ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif;  ?>

          <div class="social text-dark-blue">
            <?php echo get_template_part('/components/social/markup', 'social', ['social' => get_field('social', 'acf-options-global-fields')['social']]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BOTTOM -->
  <div class="footer-bottom md:absolute md:bottom-0 md:left-0 relative z-[1]">
    <div class="mm-sm:px-container">
      <div class="border-t border-gray-200 @sm:py-[50px] @md/lg:py-[32px] @sm:mt-[50px] md:mt-0 @md/lg:pl-[--pl-margin]">
        <?php if($privacyNav && sizeof($privacyNav) > 0):  ?>
          <ul class="flex flex-col @@:gap-y-[12px] @@:gap-x-[22px] md:flex-row aos animate-fadeinup autoscale-children">
            <?php foreach($privacyNav as $item): ?>
              <li>
                <a href="<?= get_permalink($item->ID); ?>" class="paragraph paragraph-sm paragraph-primary link-underline"><?= get_the_title($item->ID); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <img src="<?= get_template_directory_uri() ?>/assets/gradient-footer.jpg" class="absolute bottom-0 left-0 z-[0] @@:h-[400px] @md/lg:h-full w-auto"/>

</footer>