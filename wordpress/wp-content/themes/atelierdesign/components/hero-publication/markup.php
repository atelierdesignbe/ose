<?php
// BASE ----
$title = get_the_title();
$cover = get_field('cover');
if(!$cover) $cover = get_field('publication-placeholder', 'acf-options-global-fields') ;

$content = get_field('description');
$date = get_field('date_start');
$category = get_field('category');
$authors = get_field('author');
$coverState = 'fit';

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projects = get_the_terms( get_the_ID(), 'projects' );
$projectLink = get_field('publication-link', 'acf-options-global-fields');

$theme = 'theme-white';
$context = $args['context'];

$coverClass = array(
  'wrap' => 'parallax-image-wrapper aos animate-fadeinzoomout',
  'img' => 'parallax-image',
);

if ($coverState === 'fit') {
  $coverClass = array(
    'wrap' => '',
    'img' => 'image-shadow-lg aos animate-fadeinup animate-delay-400',
  );
  
}
?>


<div class="hero hero-<?= $coverState ?> <?= $theme ?> hero-cpt">
  <div class="hero-wrapper">
    <div class="relative z-10 px-container">
      <div class="hero-content">
        <div class="flex items-center @@:gap-x-2 autoscale-children aos animate-fadeinup">
          <?php if($date): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
            
          <span class="badge badge-primary badge-filled bg-dark-blue border-dark-blue text-white"><?= $category ? 'In depth' : 'Sumary publication' ?></span>
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

        <?php if ($title): ?>
          <h1 class="heading heading-primary @sm:text-[46px] @md/lg:text-[72px] font-serif font-light @sm:leading-[48px] @md/lg:leading-[69px] autoscale aos animate-fadeinup">
            <?= esc_html($title) ?>
          </h1>
        <?php endif; ?>

        <?php if ($content): ?>
          <p class="paragraph paragraph-primary paragraph-lg autoscale aos animate-fadeinup animate-delay-200">
            <?= wp_kses_post($content) ?>
          </p>
        <?php endif; ?>
        <?php if($authors): ?>
          <ul class="flex flex-wrap items-center @@:gap-[8px] autoscale-children aos animate-fadeinup animate-delay-300">
            <?php foreach($authors as $author):?>
              <li>
                <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/authors/".$author->ID : "/publications/?authors=".$author->ID; ?>" class="uppercase @@:text-[13px] font-bold text-dark-blue @@:tracking-[1px]"><?= $author->post_title; ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($types): ?>
          <ul class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-400 autoscale-children">
            <?php foreach($types as $type): ?>
              <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/types/".$type->slug : "/publications/types/".$type->slug; ?>" class="badge badge-primary badge-filled">
                <?= $type->name ?>
              </a>            
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php if($themes): ?>
          <ul class="flex items-center flex-wrap @@:gap-2 aos animate-fadeinup animate-delay-500 autoscale-children">
            <?php foreach($themes as $theme): ?>
              <li >
                <a href="<?= $projectLink ? rtrim($projectLink['url'], '/')."/themes/".$theme->slug : "/publications/themes/".$theme->slug; ?>" class="badge badge-primary badge-filled bg-yellow border-yellow"><?= $theme->name ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      
      </div>
    </div>
    <!-- COVER -->
    <?php if($coverState !== 'none'): ?>
      <div class="hero-cover">
        <div class="hero-cover-wrap <?= $coverClass['wrap'] ?>">
          <?php if($coverState === 'fit'): ?>
            <div class="hero-cover-fit">
              <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => $coverClass['img']]); ?> 
            </div>
          <?php else:  ?>
            <?php echo wp_get_attachment_image($cover['ID'], 'full', null, ['class' => $coverClass['img']]); ?> 
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
  
  <?php echo get_template_part('/components/scroll', 'scroll'); ?>

  <?= $context ?>
</div>