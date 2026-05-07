<?php
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$category = get_field('category');
$authors   = get_field('author') ?: [];
$externals = get_field('external-author')
    ? array_filter(array_map('trim', explode(',', get_field('external-author'))))
    : [];

// Construire une liste unifiée de strings HTML
$items = [];

foreach ($authors as $author) {
    $items[] = '<a href="' . esc_url(get_permalink($author->ID)) . '" class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] link-underline">'
        . esc_html($author->post_title)
        . '</a>';
}

foreach ($externals as $name) {
    $items[] = '<span class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]">'
        . esc_html($name)
        . '</span>';
}

if(!$cover) $cover = get_field('publication-placeholder', 'acf-options-global-fields') ;

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projects = get_the_terms( get_the_ID(), 'projects' );
$projectLink = get_field('publication-link', 'acf-options-global-fields');
?>
<?php
ob_start();
?>
 <div class="flex items-center @@:gap-x-2 autoscale-children aos animate-fadeinup">
    <?php if($date): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
    <?php if($types): ?>
      <ul class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-400 autoscale-children">
        <?php foreach($types as $type): ?>
          <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/types/".$type->slug : "/publications/types/".$type->slug; ?>" class="badge badge-primary badge-filled">
            <?= $type->name ?>
          </a>            
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <?php if($projects && sizeof($projects) > 0): ?>
    <ul class="flex items-center @@:gap-[8px] autoscale-children aos animate-fadeinup">
      <?php foreach($projects as $project):?>
        <li>
          <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/projects/".$project->slug : "/publications/projects/".$project->slug; ?>" class="button button-underline is-tag"><span class="button-title"><?= $project->name ?></span></a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<?php $beforeContent = ob_get_clean(); ?>

<?php
ob_start();
?>   

  <?php if ($items) : ?>
    <ul class="flex flex-wrap items-center @sm:gap-x-[8px] @md/lg:gap-x-[8px] @sm:gap-y-[4px] @md/lg:gap-y-[4px] autoscale-children aos animate-fadeinup animate-delay-300">
      <?php foreach ($items as $i => $item) : ?>
        <li class="flex items-center"><?= $item ?></li>
        <?php if ($i < count($items) - 1) : ?>
          <li class="@@:text-[13px] font-bold text-dark-blue @@:tracking-[1px] flex items-center"><span>/</span></li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

<?php $beforeDescription = ob_get_clean(); ?>
<?php
ob_start();
?> 
<?php if($themes): ?>
  <ul class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-500 autoscale-children">
    <?php foreach($themes as $theme): ?>
      <li >
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