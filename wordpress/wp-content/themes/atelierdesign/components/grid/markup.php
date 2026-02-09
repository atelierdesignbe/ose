<?php 

global $adwp;

$images = $args['grid-images'];
$content = $args['grid-content'];
$contentMiddleLeft = $args['grid-content-middle-left'];
$contentMiddleRight = $args['grid-content-middle-right'];
$contentBottomCenter = $args['grid-content-bottom-center'];
$contentBottomRight = $args['grid-content-bottom-right'];
$link = $args['grid-link'];
?>

<div class="block-grid">
  <div class="grid md:grid-cols-6 ">
    <div class="col-span-1 md:col-span-4 bg-layout-main theme-white px-container @@:py-[40px] flex items-center">
      <div class="flex flex-col md:flex-row md:justify-between items-start w-full">
        <div class="@md/lg:max-w-[494px]">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $content, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <?php if($link): ?>
          <a href="<?= $link['url']; ?>" class="button button-flat button-primary aos animate-fadeinup animate-delay-200">
            <span class="button-title"><?= $link['title']; ?></span>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-span-1 md:col-span-2 @sm:h-[290px] md:h-auto @md/lg:min-h-[398px] relative overflow-hidden">
      <div class="aos animate-fadeinzoomout absolute inset-0">
        <div class="parallax-image-wrapper h-full w-full">
          <?php echo wp_get_attachment_image($images['image-top']['ID'], "full", null, ["class" => 'object-cover  h-full w-full parallax-image']); ?>
        </div>
      </div>
    </div>
    <div class="col-span-1 md:col-span-2 bg-layout-main theme-light-blue px-container @@:py-[40px] flex items-center">
      <?php $adwp->get_template_part('_wysiwyg',  array('content' => $contentMiddleLeft, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
    </div>
    <div class="col-span-1 md:col-span-2 @sm:h-[290px] md:h-auto @md/lg:min-h-[398px] relative overflow-hidden">
      <div class="aos animate-fadeinzoomout absolute inset-0">
        <div class="parallax-image-wrapper h-full w-full">
          <?php echo wp_get_attachment_image($images['image-center']['ID'], "full", null, ["class" => 'object-cover  h-full w-full parallax-image']); ?>
        </div>
      </div>
    </div>
    <div class="col-span-1 md:col-span-2 bg-layout-main theme-dark-blue px-container @@:py-[40px] flex items-center">
      <?php $adwp->get_template_part('_wysiwyg',  array('content' => $contentMiddleRight, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
    </div>
    <div class="col-span-1 md:col-span-2 @sm:h-[290px] md:h-auto @md/lg:min-h-[398px] relative overflow-hidden">
      <div class="aos animate-fadeinzoomout absolute inset-0">
        <div class="parallax-image-wrapper h-full w-full">
          <?php echo wp_get_attachment_image($images['image-bottom']['ID'], "full", null, ["class" => 'object-cover  h-full w-full parallax-image']); ?>
        </div>
      </div>
    </div>
    <div class="col-span-1 md:col-span-2 bg-layout-main theme-yellow px-container @@:py-[40px] flex items-center">
      <?php $adwp->get_template_part('_wysiwyg',  array('content' => $contentBottomCenter, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
    </div>
    <div class="col-span-1 md:col-span-2 bg-layout-main theme-light-grey px-container @@:py-[40px] flex items-center">
      <?php $adwp->get_template_part('_wysiwyg',  array('content' => $contentBottomRight, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
    </div>
    
  </div>
</div>