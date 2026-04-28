<?php
$id   = $args['id'];
$role = get_field('role', $id);
$thumbnail_id = get_post_thumbnail_id($id);
?>
<a href="<?= esc_url(get_permalink($id)) ?>" class="member-card block">
  <div class="member-card-image">
    <?php if ($thumbnail_id) : ?>
      <?php echo wp_get_attachment_image($thumbnail_id, 'medium', false, ['class' => 'w-full h-full object-cover']); ?>
    <?php else : ?>
      <div class="w-full h-full bg-light-blue"></div>
    <?php endif; ?>
  </div>
  <div class="member-card-info">
    <p class="member-card-name heading heading-sm heading-primary"><?= esc_html(get_the_title($id)) ?></p>
    <?php if ($role) : ?>
      <p class="member-card-role paragraph paragraph-primary"><?= esc_html($role) ?></p>
    <?php endif; ?>
  </div>
</a>
