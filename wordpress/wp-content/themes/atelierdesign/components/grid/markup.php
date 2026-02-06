<?php 

global $adwp;

$content = $args['content'];
$contentOrangeTop = $args['content-orange-top'];
$contentOrangeBottom = $args['content-orange-bottom'];
$contentBlueBottom = $args['content-blue-bottom'];
$contentBlueTop = $args['content-blue-top'];
$contentBlackMiddle = $args['content-black-middle'];
$images = $args['images'];

$colorParts = explode('/', $args['section_color']);
$themeClass = "theme-" . mb_strtolower($colorParts[0], 'UTF-8');
$layoutClass = "bg-layout-" . mb_strtolower($colorParts[1], 'UTF-8');

?>

<section class="md:px-container py-section  <?= $layoutClass ?> <?= $themeClass ?>" js-grid>
  <div class="relative z-0 @@:pt-[80px]">
    <div class="grid grid-cols-1 md:grid-cols-24 autoscale relative z-[1]">
      <div class="theme-light-grey-60 <?= $colorParts[0] == 'light-grey-60' ? 'bg-white': 'bg-layout-main' ?> @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-16 mm-sm:px-container @md/lg:min-h-[366px] md:flex md:flex-col justify-center" js-grid-first>
        <?php if($content): ?>
          <div class="text-balance aos animate-fadeinup">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $content,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div>
      <div class="col-span-1 md:col-span-8 relative">
        <div class="md:absolute md:inset-0 @sm:h-[352px] md:h-auto parallax-image-wrapper" js-grid-vimage>
          <?php if($images['image-vertical']): ?>
            <?php echo wp_get_attachment_image($images['image-vertical']['ID'], 'large', null, ['class' => 'object-cover w-full h-full parallax-image', 'loading' => 'lazy', 'decoding' => 'async']); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Line 2 -->
    <div class="grid grid-cols-1 md:grid-cols-24 autoscale relative z-[1]" js-grid-last>
      <div class="bg-red theme-grey @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-8 mm-sm:px-container @md/lg:h-[396px] flex flex-col justify-end">
        <?php if($contentOrangeTop): ?>
          <div class="@md/lg:max-w-[296px] text-balance">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $contentOrangeTop,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div>
      <div class="bg-layout-main theme-<?= $colorParts[0] == 'grey' ? 'dark-grey' : 'grey' ?> @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-8 mm-sm:px-container @md/lg:h-[396px] flex flex-col justify-end" >
        <?php if($contentBlueTop): ?>
          <div class="@md/lg:max-w-[296px] text-balance">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $contentBlueTop,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div>
    </div>
    <!-- Line 3 -->
    <div class="grid grid-cols-1 md:grid-cols-24 z-10 relative autoscale">
      <div class="col-span-1 md:col-span-16 @md/lg:h-[396px] relative">
        <div class="md:absolute md:inset-0 @sm:h-[352px] md:h-full  parallax-image-wrapper">
          <?php if($images['image-horizontal']): ?> 
            <?php echo wp_get_attachment_image($images['image-horizontal']['ID'], 'large', null, ['class' => 'object-cover w-full h-full  parallax-image', 'loading' => 'lazy', 'decoding' => 'async']); ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="bg-layout-main theme-<?= $colorParts[0] == 'dark-grey' ? 'grey' : 'dark-grey' ?> @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-8 mm-sm:px-container @md/lg:h-[396px] flex flex-col justify-end">
        <?php if($contentBlackMiddle): ?>
          <div class="@md/lg:max-w-[296px] text-balance">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $contentBlackMiddle,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- LAST LINE -->
    <div class="grid grid-cols-1 md:grid-cols-24 autoscale relative z-[1]">
      <div class="bg-layout-main theme-<?= $colorParts[0] == 'grey' ? 'dark-grey' : 'grey' ?> @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-8 mm-sm:px-container @md/lg:h-[396px] flex flex-col justify-end">
        <?php if($contentBlueBottom): ?>
          <div class="@md/lg:max-w-[296px] text-balance">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $contentBlueBottom,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div> 
      <div class="bg-red theme-grey @@:py-[48px] @md/lg:px-[50px] col-span-1 md:col-span-8 mm-sm:px-container @md/lg:h-[396px] flex flex-col justify-end">
        <?php if($contentOrangeBottom): ?>
          <div class="@md/lg:max-w-[296px] text-balance">
            <?php $adwp->get_template_part('_wysiwyg', [
                'content' => $contentOrangeBottom,
                'isNested' => true,
              ]);
            ?> 
          </div>
        <?php endif; ?>
      </div>
      <div class="col-span-1 md:col-span-8 relative">
        <div class="md:absolute md:inset-0 @sm:h-[352px] md:h-full parallax-image-wrapper">
          <?php if($images['image-small']): ?>
            <?php echo wp_get_attachment_image($images['image-small']['ID'], 'large', null, ['class' => 'object-cover w-full h-full parallax-image', 'loading' => 'lazy', 'decoding' => 'async']); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="absolute top-0 left-[50%] -translate-x-[50%] -translate-y-[50%] z-0 w-[80%]" js-parallax="10">
      <?php echo get_template_part('components/lasea-svg'); ?>
    </div>
  </div>
</section>


