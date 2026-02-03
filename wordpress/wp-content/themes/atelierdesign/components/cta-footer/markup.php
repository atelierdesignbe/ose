<?php
global $adwp, $partials;
$cta = get_field('cta', 'acf-options-global-fields');
$state = $args['state'];

if ($state === 'override') $cta = $args['cta'];

if ($cta && $cta['items'] && $state !== 'disabled'):
$size = sizeof($cta['items']);
$link = $cta['link'];

?>

<div class="mx-auto w-[--size-x] md:w-full md:px-container py-section @sm:pb-[24px] md:pb-0 cta-footer">
  <div class="relative ">
    <div class="col-span-1 flex justify-end flex @sm:mb-[24px] md:absolute md:top-0 md:right-0 md:mt-0 mm-sm:hidden ">
      <p class="scroll flex aos animate-fadeinup">
        Pour aller plus loin
        <span class="scroll-rounded"></span>
      </p>
    </div>
    <div class="grid grid-cols-1 <?= $size == 1 ? 'md:grid-cols-1' : 'md:grid-cols-2' ?> gap-y-sm gap-x-sm relative items-end *:md:stagger-<?= $size ?>">
      <?php foreach($cta['items'] as $i => $item):
        $themeClass = $i === 0 ? "theme-beige-orange" : "theme-soft-green";
        if($size === 1) $themeClass = "theme-soft-green";
        // $colorParts = explode('/', $item['color']);
        // $themeClass = "theme-" . mb_strtolower($colorParts[0], 'UTF-8');
        $layoutClass = "bg-layout-main";
      ?>
        <a href="<?= $item['link']['url'] ?>" class="col-span-1 relative <?= $i === 0 && $size === 2 ? '@md/lg:mb-[75px]' : '' ?> aos animate-fadeinup stagger-delay-200" >
          <div class="relative <?= $themeClass ?> <?= $layoutClass ?> @sm:w-[298px] @sm:h-[38px] <?= $size === 1 ? '@md/lg:w-1/2 ' : '@md/lg:w-[430px]' ?> @md/lg:h-[75px] @@:rounded-t-[20px] <?= $i === 1 ? 'ml-auto cta-rect-1' : 'cta-rect-2' ?>">
            <span class="@@:size-[20px] z-[0] <?= $themeClass ?> <?= $layoutClass ?> absolute bottom-0 <?= $i === 0 ? '@@:right-[-20px]' : '@@:left-[-20px]' ?>"></span>
            <span class="@@:size-[40px] z-[1] bg-white rounded-full absolute <?= $i === 0 ? '@@:right-[-40px] bottom-[1px]' : '@@:left-[-40px] bottom-0' ?>"></span>
          </div>
          <div class="flex flex-col <?= $themeClass ?> <?= $layoutClass ?> @sm:py-[36px] @sm:px-[44px] @md/lg:py-[46px] <?= $size === 2 ? ' @md/lg:px-[70px]' : '@md/lg:px-[8.33%]' ?> <?= $i === 0 ? '@@:rounded-tr-[20px]  @@:rounded-bl-[20px]' : '@@:rounded-tl-[20px]  @@:rounded-br-[20px]' ?>  text-center mt-[-1px]">
            <div class="relative autoscale-children flex flex-col gap-y-md items-center">
              <?php $adwp->get_template_part('_wysiwyg',  array('content' => $item['content'], 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
              <span class="button-underline is-fake mm-sm:relative <?= $size === 1 ? 'md:max-w-[30%]' : 'md:max-w-[70%]' ?> text-left button-primary transition-colors duration-300 ease-out-cubic md:absolute @md/lg:-bottom-[46px] lg:translate-y-[100%] <?= $i === 0 ? 'md:right-0 md:left-auto' : 'md:left-0' ?> z-10 flex items-center @@:gap-x-4 button <?= $size === 1 ? 'md:flex md:justify-center' : '' ?>">
                <?= $partials->icon('arrow', '@@:w-[17px] @@:h-auto flex-none'); ?>
                <span class="button-title flex-auto">
                  <?= $item['link']['title'] ?>
                </span>
              </span>
            </div>
          </div>
          <div class="relative  <?= $themeClass ?> <?= $layoutClass ?> mt-[-1px]  @sm:w-[298px] @sm:h-[38px] <?= $size === 1 ? '@md/lg:w-1/2' : '@md/lg:w-[430px]' ?> @md/lg:h-[75px] @@:rounded-b-[20px] <?= $i === 0 ? 'ml-auto cta-rect-1' : 'cta-rect-2' ?>" >
            <span class="@@:size-[20px] z-[0]  <?= $themeClass ?> <?= $layoutClass ?> absolute top-0 <?= $i === 1 ? '@@:right-[-20px]' : '@@:left-[-20px]' ?>"></span>
            <span class="@@:size-[40px] z-[1] bg-white rounded-full absolute <?= $i === 1 ? '@@:right-[-40px] top-[1px]' : '@@:left-[-40px] top-[1px]' ?>"></span>
          </div>
        </a>
      <?php endforeach; ?>
  </div>  
</div>
<?php endif; ?>