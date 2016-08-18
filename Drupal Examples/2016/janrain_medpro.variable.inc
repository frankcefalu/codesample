<?php
/**
 * @file
 * Variable file.
 */

/**
 * Implements hook_variable_group_info().
 */
function janrain_medpro_variable_group_info() {
  $groups['janrain_medpro'] = array(
    'title' => t('MedPro'),
    'description' => t('MedPro variables.'),
    'access' => 'administer site configuration',
    'path' => array('admin/config/people/janrain/medpro/error_messages', 'admin/config/people/janrain/medpro/promo_codes'),
  );
  return $groups;
}

/**
 * Implements hook_variable_info().
 */
function janrain_medpro_variable_info($options) {
  $variables['janrain_medpro_promo_code_error_message'] = array(
    'type' => 'text_format',
    'title' => t('Promo code error message', array(), $options),
    'description' => t('Promo code error message', array(), $options),
    'required' => TRUE,
    'localize' => TRUE,
    'group' => 'janrain_medpro',
  );

  $variables['janrain_medpro_registration_top_message'] = array(
    'type' => 'text_format',
    'title' => t('Registration Top message.', array(), $options),
    'description' => t('Top message shown on registration form.', array(), $options),
    'required' => TRUE,
    'localize' => TRUE,
    'group' => 'janrain_medpro',
  );

  $variables['janrain_medpro_login_top_message'] = array(
    'type' => 'text_format',
    'title' => t('Login Top message.', array(), $options),
    'description' => t('Top message shown on login form.', array(), $options),
    'required' => TRUE,
    'localize' => TRUE,
    'group' => 'janrain_medpro',
  );

  $variables['janrain_medpro_fail_messages_[number]'] = array(
    'type' => 'multiple',
    'title' => t('Fail messages', array(), $options),
    'description' => t('Fail messages', array(), $options),
    'multiple callback' => '_janrain_medpro_variable_multiple_fail_messages',
    'repeat' => array(
      'type' => 'text_format',
    ),
    'required' => TRUE,
    'localize' => TRUE,
    'group' => 'janrain_medpro',
  );

  return $variables;
}
