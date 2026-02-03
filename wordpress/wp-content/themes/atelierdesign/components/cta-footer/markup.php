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
  
</div>
<?php endif; ?>