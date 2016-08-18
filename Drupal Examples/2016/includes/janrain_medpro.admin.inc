<?php
/**
 * @file
 * Administration.
 */

/**
 * Admin configuration form.
 */
function janrain_medpro_admin_form($form) {

  $form['janrain_medpro_webserviceid_url'] = array(
    '#type' => 'textfield',
    '#title' => t('WebServiceID Url'),
    '#default_value' => variable_get('janrain_medpro_webserviceid_url', ''),
    '#required' => TRUE,
  );

  $form['janrain_medpro_webserviceid_username'] = array(
    '#type' => 'textfield',
    '#title' => t('WebServiceID UserName'),
    '#default_value' => variable_get('janrain_medpro_webserviceid_username', ''),
    '#required' => TRUE,
  );

  $form['janrain_medpro_webserviceid_password'] = array(
    '#type' => 'textfield',
    '#title' => t('WebServiceID Password'),
    '#default_value' => variable_get('janrain_medpro_webserviceid_password', ''),
    '#required' => TRUE,
  );

  $form['janrain_medpro_validate_token_check'] = array(
    '#type' => 'checkbox',
    '#title' => t('Restrict validate url with token check'),
    '#default_value' => variable_get('janrain_medpro_validate_token_check', FALSE),
    '#description' => t('Restrict access to validate url with token check. Keep in mind that anonymous user cache may impact this functionality.'),
  );

  $form['janrain_medpro_validate_session'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use user session for anonymous access token check'),
    '#default_value' => variable_get('janrain_medpro_validate_session', TRUE),
    '#description' => t('If checked the validate will use session if unchecked will use ip address.'),
    '#states' => array(
      'invisible' => array(
        ':input[name="janrain_medpro_validate_token_check"]' => array('checked' => FALSE),
      ),
    ),
  );

  return system_settings_form($form);
}

/**
 * Error messages form.
 */
function janrain_medpro_error_messages_form($form) {
  $form['janrain_medpro_fail_limit'] = array(
    '#type' => 'textfield',
    '#title' => t('Fail limit count'),
    '#default_value' => variable_get('janrain_medpro_fail_limit', 0),
    '#description' => t('Amount of times <b>Cannot Find Practitioner</b> error is returned before showing message #3. Enter "0" for unlimited.'),
  );

  $default_value = variable_get('janrain_medpro_login_top_message', array());

  $form['janrain_medpro_login_top_message'] = array(
    '#type' => 'text_format',
    '#base_type' => 'textarea',
    '#title' => t('Login Top message.'),
    '#format' => !empty($default_value['format']) ? $default_value['format'] : 'filtered_html',
    '#default_value' => !empty($default_value['value']) ? $default_value['value'] : '',
    '#description' => t('Top message shown on login form.'),
  );

  $default_value = variable_get('janrain_medpro_registration_top_message', array());
  $form['janrain_medpro_registration_top_message'] = array(
    '#type' => 'text_format',
    '#base_type' => 'textarea',
    '#title' => t('Registration Top message.'),
    '#format' => !empty($default_value['format']) ? $default_value['format'] : 'filtered_html',
    '#default_value' => !empty($default_value['value']) ? $default_value['value'] : '',
    '#description' => t('Top message shown on registration form.'),
  );

  $default_value = variable_get('janrain_medpro_fail_messages_1', array());
  $form['janrain_medpro_fail_messages_1'] = array(
    '#type' => 'text_format',
    '#base_type' => 'textarea',
    '#title' => t('Mesage #1'),
    '#format' => !empty($default_value['format']) ? $default_value['format'] : 'filtered_html',
    '#default_value' => !empty($default_value['value']) ? $default_value['value'] : '',
    '#description' => t('Shown when Overall Sampleability is not <b>Y</b>'),
  );

  $default_value = variable_get('janrain_medpro_fail_messages_2', array());
  $form['janrain_medpro_fail_messages_2'] = array(
    '#type' => 'text_format',
    '#base_type' => 'textarea',
    '#title' => t('Mesage #2'),
    '#format' => !empty($default_value['format']) ? $default_value['format'] : 'filtered_html',
    '#default_value' => !empty($default_value['value']) ? $default_value['value'] : '',
    '#description' => t('Shown on 1<sup>st</sup> and 2<sup>nd</sup> attempt when <b>Cannot Find Practitioner</b> is returned.'),
  );

  $default_value = variable_get('janrain_medpro_fail_messages_3', array());
  $form['janrain_medpro_fail_messages_3'] = array(
    '#type' => 'text_format',
    '#base_type' => 'textarea',
    '#title' => t('Mesage #3'),
    '#format' => !empty($default_value['format']) ? $default_value['format'] : 'filtered_html',
    '#default_value' => !empty($default_value['value']) ? $default_value['value'] : '',
    '#description' => t('Shown on 3<sup>rd</sup> attempt and after that when <b>Cannot Find Practitioner</b> is returned.'),
  );

  return system_settings_form($form);
}

/**
 * Fields config form.
 */
function janrain_medpro_fields_form($form) {
  $fields_defaults = variable_get('janrain_medpro_validate_fields', array());

  $form['janrain_medpro_validate_fields'] = array(
    '#tree' => TRUE,
  );

  $fields = array(
    'FirstName' => array('required' => TRUE, 'label' => t('FirstName')),
    'MiddleInit' => array('required' => FALSE, 'label' => t('MiddleInit')),
    'LastName' => array('required' => TRUE, 'label' => t('LastName')),
    'StateLicNum' => array('required' => TRUE, 'label' => t('StateLicNum')),
    'LicStateId' => array('required' => TRUE, 'label' => t('LicStateId')),
    'ReasonCode' => array('required' => TRUE, 'label' => t('ReasonCode')),
    'Sampleability' => array('required' => TRUE, 'label' => t('Sampleability')),
  );

  foreach ($fields as $field => $field_options) {
    $example = drupal_strtolower(drupal_substr($field, 0, 1)) . drupal_substr($field, 1);
    $form['janrain_medpro_validate_fields'][$field] = array(
      '#type' => 'fieldset',
      '#title' => $field_options['label'],
      '#collappsible' => TRUE,
      '#collpased' => FALSE,
    );
    $form['janrain_medpro_validate_fields'][$field]['key'] = array(
      '#type' => 'textfield',
      '#title' => t('!field key', array('!field' => $field)),
      '#default_value' => !empty($fields_defaults[$field]['key']) ? $fields_defaults[$field]['key'] : '',
      '#description' => t('Enter the field id part without the screen info.<br>For example enter <b>!example</b> instead of <b>capture_traditionalRegistration_!example</b>', array('!example' => $example)),
      '#required' => $field_options['required'],
    );

    $form['janrain_medpro_validate_fields'][$field]['required'] = array(
      '#type' => 'checkbox',
      '#title' => t('!field required', array('!field' => $field)),
      '#default_value' => !empty($fields_defaults[$field]['required']) ? $fields_defaults[$field]['required'] : 0,
      '#description' => t('Check if the field "!field" should be required.', array('!field' => $field)),
    );

    $form['janrain_medpro_validate_fields'][$field]['required_message'] = array(
      '#type' => 'textfield',
      '#title' => t('!field required error message', array('!field' => $field)),
      '#default_value' => !empty($fields_defaults[$field]['required_message']) ? $fields_defaults[$field]['required_message'] : '',
      '#states' => array(
        'invisible' => array(
          ':input[name="janrain_medpro_validate_fields[' . $field . '][required]"]' => array('checked' => FALSE),
        ),
      ),
    );
  }

  return system_settings_form($form);
}

/**
 * Promo codes settings form.
 */
function janrain_medpro_promo_codes_form($form) {
  $form['janrain_medpro_promo_codes'] = array(
    '#type' => 'textarea',
    '#title' => t('Promo codes'),
    '#default_value' => variable_get('janrain_medpro_promo_codes', ''),
    '#description' => t('Enter comma separated values of valid promo codes.'),
  );

  $form['janrain_medpro_promo_codes_error_message'] = array(
    '#type' => 'textarea',
    '#title' => t('Promo codes error message'),
    '#default_value' => variable_get('janrain_medpro_promo_codes_error_message', ''),
  );

  return system_settings_form($form);
}
