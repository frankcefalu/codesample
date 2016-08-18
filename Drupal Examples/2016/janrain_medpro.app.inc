<?php

/**
 * @file
 * Configuration form for Master HCP app.
 */

/**
 * Implements hook_apps_app_info().
 */
function janrain_medpro_apps_app_info() {
  return array(
    'configure form' => 'janrain_medpro_configure',
  );
}

/**
 * Configure form for jjbos_foresee app.
 *
 * @param array $form
 *   Standard Drupal form array.
 * @param array $form_state
 *   Standard Drupal form_state array.
 *
 * @return array
 *   Returns updated Drupal form array.
 */
function janrain_medpro_configure($form, $form_state) {
  // Fetch the system variables containing foresee configuration values.
  $foresee_id = variable_get('foresee_id');
  $foresee_logo_fid = variable_get('foresee_logo_fid');
  $foresee_sitename = variable_get('foresee_section');
  $foresee_percentages = unserialize(variable_get('foresee_sp_values'));
  $foresee_loyalties = unserialize(variable_get('foresee_lf_values'));
// Foresee App Configuration page.
  $form['setup'] = array(
    '#type' => 'fieldset',
    '#title' => t('Master HCP Checklist'),
  );
  $form['setup']['table'] = array(
    '#theme' => 'table',
    '#header' => array("Status", "Step", "Instructions", "Action"),
    '#rows' => array(
      'configuration-set' => array(
        'data' => array(
          theme("image", array("path" => "misc/watchdog-ok.png", "alt" => t('OK'))),
          t("Janrain Configuration Set"),
          t("You must choose the configuration set for this janrain flow."),
          l(t('Update ID on Configuration Page'), 'admin/config/people/janrain/sets', array('query' => drupal_get_destination())),
        ),
      ),
      'section' => array(
        'data' => array(
          theme("image", array("path" => "misc/watchdog-ok.png", "alt" => t('OK'))),
          t("Medpro Username/Password"),
          t('You need to update the username and password for medpro.'),
          l(t('Update on Configuration Page'),
            'admin/config/people/janrain/medpro',
            array('query' => drupal_get_destination())),
        ),
      ),
    ),
  );

  return $form;
}
