<?php

namespace Drupal\carlylegirms_clickthrough\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;

/**
 * Builds out the user agreement NDA Form.
 */
class NdaAgreementForm extends FormBase
{

  /**
   * Getter method for Form ID.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId()
  {
    return 'nda_agreement_form';
  }

  /**
   * Build the NDA form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $ndaConfig = \Drupal::config('carlylegirms_clickthrough.adminsettings');
    // Build NDA Agreement action.

    $form['agreement'] = [
      '#title' => $this->t($ndaConfig->get('nda_sub_heading')),
      '#markup' => $this->t($ndaConfig->get('nda_message.value')),
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('ACCEPT'),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'nda-agreement-accept',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'submitModalFormAjax'],
        'event' => 'click',
      ],
    ];

    // Library
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    
    return $form;
  }

  /**
   * Implements a form submit handler.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Set a cookie and redirect upon agreement.
    user_cookie_save(['carlylegirms' => 'carlyle_nda']);
    $url = Url::fromRoute('<front>');
    $form_state->setRedirectUrl($url);
  }

  /**
   * {@inheritdoc}
   */
  public function submitModalFormAjax(array &$form, FormStateInterface $form_state)
  {
    $command = new CloseModalDialogCommand();
    $response = new AjaxResponse();
    $response->addCommand($command);
    return $response;
  }
}
