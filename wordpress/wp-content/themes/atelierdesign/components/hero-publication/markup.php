<?php
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$category = get_field('category');
$authors   = get_field('author') ?: [];


if(!$cover) $cover = get_field('publication-placeholder', 'acf-options-global-fields') ;

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
// $projects = get_the_terms( get_the_ID(), 'projects' );
$projectLink = get_field('publication-link', 'acf-options-global-fields');
?>
<?php
ob_start();
?>
 <div class="flex items-center @@:gap-x-2 autoscale-children ">
    <?php if($date): ?> <span class="badge badge-primary badge-outlined aos animate-fadeinup"><?= $date ?></span><?php endif; ?>
    <?php if($types): ?>
      <ul class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-100 autoscale-children">
        <?php foreach($types as $type): ?>
          <span class="badge badge-primary badge-filled bg-dark-blue text-white border-dark-blue">
            <?= $type->name ?>
          </span>            
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

<?php $beforeContent = ob_get_clean(); ?>

<?php
ob_start();
?>   

  <?php if ($authors) : ?>
    <ul class="flex flex-wrap items-center @sm:gap-x-[8px] @md/lg:gap-x-[8px] @sm:gap-y-[4px] @md/lg:gap-y-[4px] autoscale-children  ">
      <?php foreach ($authors as $i => $author) : ?>
        <li class="flex items-center aos animate-fadeinup" style="animation-delay: <?= ($i * 100) + 200 ?>ms">
          <?php if($author->post_type == 'author'):  ?>
            <a href="<?= esc_url(get_permalink($author->ID)) ?>" class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] link-underline"><?= esc_html($author->post_title) ?> </a>
          <?php else:  ?>
            <span class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]"><?= esc_html($author->post_title) ?> </span>
          <?php endif;  ?>
        </li>
        <?php if ($i < count($authors) - 1) : ?>
          <li class="@@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] flex items-center aos animate-fadeinup"  style="animation-delay: <?= ($i * 125) + 200 ?>ms"><span>/</span></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

<?php $beforeDescription = ob_get_clean(); ?>
<?php
ob_start();
?> 
<?php if($themes): ?>
  <ul class="flex items-center flex-wrap @@:gap-2 autoscale-children">
    <?php foreach($themes as $i => $theme): ?>
      <li class="aos animate-fadeinup" style="animation-delay: <?= ($i * 100) + 300 ?>ms">
        <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/themes/".$theme->slug : "/publications/themes/".$theme->slug; ?>" class="badge badge-primary badge-filled bg-yellow border-yellow"><?= $theme->name ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php $afterContent = ob_get_clean(); ?>


<?php 
  echo get_template_part(
    '/components/hero/markup', 
    null, 
    [
      'title' => $title,
      'cover' => $cover,
      'content' => $description,
      'cover-status' => 'fit',
      'beforeContent' => $beforeContent,
      'afterContent' => $afterContent,
      'beforeDescription' => $beforeDescription,
      'social'  => false,
    ]);
?>