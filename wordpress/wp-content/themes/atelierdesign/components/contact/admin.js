(function ($) {
  'use strict';

  const { formEmailMap, fallback, fieldKeySelect, fieldKeyEmail } =
    window.adContactAdmin || {};

  if (!formEmailMap || !fieldKeySelect || !fieldKeyEmail) return;

  function updateInstructions(formId, $acfField) {
    const data  = formEmailMap[formId];
    const email = data ? data.email : fallback;

    // Remonte au conteneur commun (.acf-fields) puis cherche le champ repeater
    const $container  = $acfField.closest('.acf-fields');
    const $emailField = $container.find('[data-key="' + fieldKeyEmail + '"]').first();
    if (!$emailField.length) return;

    // Cherche ou crée .acf-instructions dans .acf-label du repeater
    let $instr = $emailField.children('.acf-label').find('.acf-instructions');
    if (!$instr.length) {
      $instr = $('<p class="acf-instructions"></p>');
      // Insère après le <label> à l'intérieur de .acf-label (structure ACF standard)
      $emailField.children('.acf-label').children('label').after($instr);
    }

    $instr.html(
      email
        ? 'Optional — if left blank, emails will only be sent to: <strong>' + email + '</strong>'
        : 'Optional — add extra recipients.'
    );
  }

  function bindWrapper($wrapper) {
    if ($wrapper.data('adBound')) return;
    $wrapper.data('adBound', true);

    const $select = $wrapper.find('select');

    const initial = $select.val();
    if (initial) updateInstructions(initial, $wrapper);

    $select.on('change select2:select select2:unselect', function (e) {
      const val = (e.type === 'select2:select' && e.params && e.params.data)
        ? e.params.data.id
        : $(this).val();
      updateInstructions(val, $wrapper);
    });
  }

  function bindInRoot(root) {
    $(root).find('[data-key="' + fieldKeySelect + '"]').each(function () {
      bindWrapper($(this));
    });
  }

  // Quand acf-lazy-layouts restaure un layout (user ouvre un bloc)
  $(document).on('ad:lazy-layout-restored', '.layout', function () {
    bindInRoot(this);
  });

  // Quand un nouveau bloc est ajouté via le bouton +
  if (typeof acf !== 'undefined') {
    acf.addAction('append', function ($el) {
      setTimeout(function () {
        bindInRoot($el[0] || $el);
      }, 0);
    });
  }

})(jQuery);
