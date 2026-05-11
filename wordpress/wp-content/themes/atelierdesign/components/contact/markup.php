<?php global $adwp;

// Résolution de l'email destinataire (chaîne : form spécifique → global → admin WP)
$primary_email = resolve_form_email($args['form_id'] ?? 0);

// Repeater contact-email → tableau de rows [['email' => '...']]
// On extrait, valide et joint avec des virgules
$additional_email = '';
$rows = $args['contact-email'] ?? [];
if (!empty($rows) && is_array($rows)) {
  $emails = [];
  foreach ($rows as $row) {
    $e = trim($row['email'] ?? '');
    if ($e && is_email($e)) {
      $emails[] = sanitize_email($e);
    }
  }
  $additional_email = implode(',', $emails);

}

$args['form_id'] = $args['form_id'] ?? 1;
?>
<section class="theme-white bg-layout-main py-section">
    <div class="container">
      <div class="grid grid-cols-12 md:grid-cols-24 @sm:gap-y-[24px] @md/lg:gap-y-[24px]">
        <div class="col-span-12 md:col-span-8">
          <?php $adwp->get_template_part('_wysiwyg',  array('content' => $args['content'], 'inside' => true, 'isNested' => true, 'aos' => '','layout_settings' => ['isFullWidth' => true ] )); ?>
        </div>
        <div class="col-span-12 md:col-span-14 md:col-start-11">
          <?php if($args['form_id']): ?>
            <div
              class="aos animate-fadeinup animate-delay-200"
              js-form
              data-email="<?= esc_attr($primary_email); ?>"
              <?php if($additional_email): ?>
              data-email-cc="<?= esc_attr($additional_email); ?>"
              <?php endif; ?>
            >
              <?php echo do_shortcode("[formidable id='".$args['form_id']."']"); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>