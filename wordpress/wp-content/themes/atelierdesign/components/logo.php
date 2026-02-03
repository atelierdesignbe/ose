<?php
  $logo = $args['logo'];
  $logo_class = $args['logo_class'] ?? '';
  $img_class = $args['img_class'] ?? '';
  if ($logo):
?>
<a href="<?php echo home_url(); ?>" class="<?php echo $logo_class; ?>">
  <?php
    $isSVG = strtolower(pathinfo($logo['filename'], PATHINFO_EXTENSION)) === 'svg';

    if ($isSVG): 
      $svg_file = get_attached_file($logo['ID']);
      $svg = file_get_contents($svg_file);
      $svg = preg_replace(
        '/<svg\b([^>]*)>/',
        '<svg$1 class="'. $img_class .'">',
        $svg,
        1
    );
      echo $svg;
    else :
      echo wp_get_attachment_image($logo['ID'], "full", null, ["class" => $img_class]);
    endif;
  ?>
  
</a>
<?php endif; ?>