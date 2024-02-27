<?php

namespace Drupal\carlylegirms_clickthrough\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * NDA agreement page form.
 */
class NdaAgreementConfigFormCaseStudies extends ConfigFormBase {

  /**
   * The nda config settings.
   *
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'carlylegirms_clickthrough.adminsettings_case_studies',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nda_settings_form_case_studies';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('carlylegirms_clickthrough.adminsettings_case_studies');

    $form['nda_label_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NDA Title'),
      '#description' => $this->t('Enter the title for the nda agreement.'),
      '#default_value' => $config->get('nda_label_name'),
    ];
    $form['nda_sub_heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('NDA Sub Heading'),
      '#description' => $this->t('Enter the sub-heading for the nda subheading.'),
      '#default_value' => $config->get('nda_sub_heading'),
    ];
    $form['nda_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('NDA message'),
      '#format' => $config->get('format'),
      '#description' => $this->t('NDA message to display to users when they login'),
      '#default_value' => $config->get('nda_message.value'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('carlylegirms_clickthrough.adminsettings_case_studies')
      ->set('nda_message.value', $values['nda_message']['value'])
      ->set('nda_message.format', $values['nda_message']['format'])
      ->set('nda_label_name', $values['nda_label_name'])
      ->set('nda_sub_heading', $values['nda_sub_heading'])
      ->save();

    parent::submitForm($form, $form_state);

  }

}
