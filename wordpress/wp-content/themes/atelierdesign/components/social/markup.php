<?php 
global $partials;
$social = $args['social'];
if ($social): ?>
<ul class="social autoscale-children *:md:stagger-<?= sizeof($social['items']) ?>">
  <?php foreach($social['items'] as $item):  ?>
    <li class="aos animate-fadeinup animate-delay-100">
      <a href="<?= $item['url']; ?>" class="social-item" target="_blank" rel="noopener noreferrer">
        <?= $partials->icon($item['type'], '') ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>