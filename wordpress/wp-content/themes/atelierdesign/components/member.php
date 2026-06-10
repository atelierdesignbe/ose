<?php
$id          = $args['id'];
$role        = get_field('role', $id);
$cover       = get_field('cover', $id);
$is_archived = has_term( 'archived', 'member_status', $id );
if ( ! $cover ) $cover = get_field('team-placeholder', 'acf-options-global-fields');

$tag   = $is_archived ? 'div' : 'a';
$attrs = $is_archived ? '' : ' href="' . esc_url(get_permalink($id)) . '"';
?>
<<?= $tag . $attrs ?> class="member-card block<?= $is_archived ? ' is-archived' : '' ?>">
  <div class="member-card-image">
    <?php if ( $cover['ID'] ) : ?>
      <?php echo wp_get_attachment_image($cover['ID'], 'medium', false, ['class' => 'w-full h-full object-cover']); ?>
    <?php else : ?>
      <div class="w-full h-full bg-light-blue"></div>
    <?php endif; ?>
  </div>
  <div class="member-card-info autoscale-children">
    <p class="member-card-name paragraph-lg paragraph-primary"><?= esc_html(get_the_title($id)) ?></p>
    <?php if ($role) : ?>
      <p class="member-card-role paragraph-md paragraph-primary"><?= esc_html($role) ?></p>
    <?php endif; ?>
  </div>
</<?= $tag ?>>
