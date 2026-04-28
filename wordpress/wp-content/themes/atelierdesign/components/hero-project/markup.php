<?php
// BASE ----
$title = get_the_title();
$cover = get_field('cover');
$description = get_field('description');
$date = get_field('date_start');
$coverState = get_field('cover-status');
if(!$cover) $coverState = 'none';

$themes = get_the_terms( get_the_ID(), 'themes' );
$types = get_the_terms( get_the_ID(), 'types' );
$projectLink = get_field('project-link', 'acf-options-global-fields');

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

        <div class="flex items-center @@:gap-x-2 aos animate-fadeinup">
          <?php if($date): ?> <span class="badge badge-primary badge-outlined"><?= $date ?></span><?php endif; ?>
          <a href="<?= $projectLink ? $projectLink['url'] : '/projects/' ?>" class="badge badge-primary badge-filled bg-dark-blue text-white border-dark-blue">Project</a>
        </div>

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