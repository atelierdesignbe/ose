<?php
/**
 * SI ACF ET FORMIDABLE FORM
 */

 add_filter('acf/load_field/key=field-form-formidable', 'load_formidable_forms_into_select');
 add_filter('acf/load_field/key=field-gloabl-job-offer-form-id', 'load_formidable_forms_into_select');
 add_filter('acf/load_field/key=field-contact-form-id', 'load_formidable_forms_into_select');
 add_filter('acf/load_field/key=field-contact-formidable', 'load_formidable_forms_into_select');
 
 function load_formidable_forms_into_select($field) {
 
     // Réinitialiser les choix
     $field['choices'] = [];
 
     // Vérifier que Formidable Forms est actif
     if ( class_exists( 'FrmForm' ) ) {
 
         // Récupérer tous les formulaires Formidable
         $forms = FrmForm::getAll([
           'is_template' => 0,
           'status'      => 'published',   // '' = publié — exclut 'trash' et les drafts
       ]);
 
         // Boucler sur les formulaires pour remplir les choix
         if ( !empty($forms) ) {
             foreach ($forms as $form) {
                 $field['choices'][$form->id] = $form->name;
             }
         }
 
         // Un seul formulaire disponible → auto-sélection
         if ( count($field['choices']) === 1 ) {
             $field['default_value'] = array_key_first($field['choices']);
         }
 
     } else {
         $field['choices'][''] = '⚠️ Formidable Forms non détecté';
     }
 
     return $field;
 }
 
 // Restrict some fields
 // add_filter( 'acf/prepare_field/key=field-form-formidable', function ( $field ) {
 //   // Remplace 'administrator' par le rôle autorisé à voir ce champ
 //   if ( ! current_user_can( 'administrator' ) ) {
 //       return false; // masque le champ (et sa valeur n'est pas envoyée)
 //   }
 //   return $field;
 // } );
 // add_filter( 'acf/prepare_field/key=field-cta-footer-gallery', function ( $field ) {
 //   // Remplace 'administrator' par le rôle autorisé à voir ce champ
 //   if ( ! current_user_can( 'administrator' ) ) {
 //       return false; // masque le champ (et sa valeur n'est pas envoyée)
 //   }
 //   return $field;
 // } );
 
 // add_filter( 'acf/prepare_field/key=field-job-offer-form-id', function ( $field ) {
 //   // Remplace 'administrator' par le rôle autorisé à voir ce champ
 //   if ( ! current_user_can( 'administrator' ) ) {
 //       return false; // masque le champ (et sa valeur n'est pas envoyée)
 //   }
 //   return $field;
 // } );
 
 // Masquer le sélecteur si un seul formulaire disponible (inutile de choisir)
 add_filter('acf/prepare_field/key=field-contact-formidable', function($field) {
     if ( !empty($field['choices']) && count($field['choices']) === 1 ) {
         $field['wrapper']['class'] = trim(($field['wrapper']['class'] ?? '') . ' acf-hidden');
     }
     return $field;
 });
 
 add_shortcode('get_referent_email', function($atts) {
     
   $atts = shortcode_atts([
       // 'post_id' => get_the_ID(),
       'slug'    => '', 
   ], $atts);
 
   // $post_id = $atts['post_id'];
   $slug = $atts['slug'] ?? '';
 
 
   // Chaîne de fallback — dans l'ordre de priorité
   $globalForm = adui_options($slug);
   $sources = [
       // Champ ACF sur le post courant
       fn() => adui_options($slug)[$slug.'-email'],
 
       fn() => adui_options('form-contact-email'),
       
       // Fallback final : email admin WordPress
       fn() => get_option('admin_email'),
   ];
 
   foreach ($sources as $source) {
       $email = $source();
       if (!empty($email) && is_email($email)) {
           return sanitize_email($email);
       }
   }
 
   return ''; // Rien trouvé
 });
 
 
 // =============================================================================
 // FORMIDABLE — Automatisation Email Sender
 // =============================================================================
 
 /**
  * Shortcode [default-email] utilisable dans les notifications Formidable.
  * Résolution : adui_options('form-contact-email') → admin_email
  */
 add_filter('frm_replace_shortcodes', function ($content, $entry = null, $form = null) {
   if (strpos($content, '[default-email]') === false) return $content;
   $email = adui_options('form-contact-email') ?: get_option('admin_email');
   return str_replace('[default-email]', sanitize_email($email), $content);
 }, 10, 3);
 
 /**
  * Supprime la notification email par défaut de Formidable :
  * on crée la nôtre (avec [email-sender]) via frm_build_new_form.
  */
 add_filter('frm_create_default_email_action', '__return_false');
 
 /**
  * À la création d'un formulaire :
  *  1. Ajoute un champ caché "Email Sender" (field_key: email-sender)
  *  2. Crée une notification email par défaut qui utilise ce champ comme destinataire
  *
  * Hooks Formidable 5.3+ / 6.x :
  *   frm_build_new_form       → nouveau form créé via l'UI ou l'API
  *   frm_update_form          → sauvegarde du builder (filet de sécurité, idempotent)
  *   frm_after_duplicate_form → form dupliqué (skippé si le champ existe déjà)
  */
 add_action('frm_build_new_form',       'ad_setup_form_email_sender', 20, 1);
 add_action('frm_update_form',          'ad_setup_form_email_sender', 20, 1); // ($id, $values) — on n'utilise que $id
 add_action('frm_after_duplicate_form', 'ad_setup_form_email_sender', 20, 1);
 
 function ad_setup_form_email_sender($form_id) {
   $form_id = (int) $form_id;
   if (!$form_id) return;
   if (!class_exists('FrmField')) return;
 
   global $wpdb;
 
   // ── 1. Champ caché "Email Sender" ─────────────────────────────────────────
   // Cherche par name + form_id (field_key est unique globalement en DB,
   // donc on ne peut pas forcer 'email-sender' si un autre form l'utilise déjà)
   $field_row = $wpdb->get_row($wpdb->prepare(
     "SELECT id, field_key FROM {$wpdb->prefix}frm_fields
      WHERE name = 'Email Sender' AND form_id = %d LIMIT 1",
     $form_id
   ));
 
   if ($field_row) {
     $field_key = $field_row->field_key;
   } else {
     // FrmField::create() gère l'unicité globale de field_key en ajoutant un
     // suffixe si besoin (email-sender → email-sender2 → …). On lit la clé
     // réelle après création pour l'utiliser dans la notification.
     $field_id = FrmField::create([
       'type'          => 'hidden',
       'name'          => 'Email Sender',
       'field_key'     => 'email-sender',
       'form_id'       => $form_id,
       'required'      => 0,
       'default_value' => '',
       'options'       => [],
       'field_options' => [],   // requis en Formidable 6.x
     ]);
 
     if (!$field_id) return;
 
     $field_key = $wpdb->get_var($wpdb->prepare(
       "SELECT field_key FROM {$wpdb->prefix}frm_fields WHERE id = %d",
       (int) $field_id
     ));
   }
 
   if (empty($field_key)) return;
 
   // ── 2. Notification email par défaut ──────────────────────────────────────
   // Idempotent : une seule notification par formulaire.
   // En Formidable 6.x le type d'action est dans post_excerpt (pas post_mime_type)
   $action_exists = $wpdb->get_var($wpdb->prepare(
     "SELECT ID FROM {$wpdb->posts}
      WHERE post_type = 'frm_form_actions'
        AND post_excerpt = 'email'
        AND menu_order = %d LIMIT 1",
     $form_id
   ));
 
   if ($action_exists) return;
 
   if (class_exists('FrmFormActionsController')) {
     // API officielle Formidable — gère JSON-encode + filtres content_save_pre
     $action_control = FrmFormActionsController::get_form_actions('email');
     if ($action_control && !is_array($action_control)) {
       $action = $action_control->prepare_new($form_id);
       // Clés réelles du get_defaults() de FrmEmailAction :
       $action->post_content['email_to']      = '[' . $field_key . ']';
       $action->post_content['from']          = '[sitename] <[default-email]>';
       $action->post_content['reply_to']      = '';
       $action->post_content['cc']            = '';
       $action->post_content['bcc']           = '';
       $action->post_content['email_subject'] = 'New message from [sitename]';
       $action->post_content['email_message'] = '[default-message]';
       $action->post_content['event']         = ['create'];
       $action_control->save_settings($action);
       return;
     }
   }
 
   // Fallback : insertion directe JSON
   global $wp_filter;
   $saved_filters = $wp_filter['content_save_pre'] ?? null;
   remove_all_filters('content_save_pre');
 
   wp_insert_post([
     'post_title'   => 'Send Email',
     'post_status'  => 'publish',
     'post_type'    => 'frm_form_actions',
     'post_excerpt' => 'email',          // type d'action en Formidable 6.x
     'post_name'    => $form_id . '_email_1',
     'menu_order'   => $form_id,
     'post_content' => json_encode([
       'email_to'      => '[' . $field_key . ']',
       'from'          => '[sitename] <[default-email]>',
       'reply_to'      => '',
       'cc'            => '',
       'bcc'           => '',
       'email_subject' => 'New message from [sitename]',
       'email_message' => '[default-message]',
       'event'         => ['create'],
       'plain_text'    => 0,
       'inc_user_info' => 0,
       'email_style'   => '',
     ]),
   ]);
 
   if ($saved_filters) $wp_filter['content_save_pre'] = $saved_filters;
 }
 