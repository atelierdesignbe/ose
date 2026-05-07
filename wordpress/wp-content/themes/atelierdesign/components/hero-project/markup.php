<?php
$override = $args['hero-override'];
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$coverState = get_field('cover-status');

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

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projectLink = get_field('project-link', 'acf-options-global-fields');
if (!$cover) $coverState = 'none';

?>
<?php
ob_start();
?>
<div class="flex items-center @@:gap-x-2 aos animate-fadeinup">
  <?php if($date): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
  <a href="<?= $projectLink ? $projectLink['url'] : '/projects/' ?>" class="badge badge-primary badge-filled bg-dark-blue text-white border-dark-blue">Project</a>
</div>
<?php $beforeContent = ob_get_clean(); ?>
<?php
ob_start();
?>
<?php if($types): ?>
        <ul  class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-300 autoscale-children">
          <?php foreach($types as $type): ?>
            <li>
              <a href="<?= $projectLink ? rtrim($projectLink['url'], '/').'/types/'.$type->slug : '/projects/types/'.$type->slug; ?>" class="badge badge-primary badge-filled">
                <?= $type->name ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <?php if($themes): ?>
        <ul  class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-400 autoscale-children">
          <?php foreach($themes as $theme): ?>
            <li>
              <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/themes/".$theme->slug : "/projects/themes/".$theme->slug ?>" class="badge badge-secondary badge-filled bg-yellow border-yellow"><?= $theme->name ?></a>
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
      'cover-status' => $coverState,
      'beforeContent' => $beforeContent,
      'afterContent' => $afterContent,
      'social'  => false,
    ]);
?>