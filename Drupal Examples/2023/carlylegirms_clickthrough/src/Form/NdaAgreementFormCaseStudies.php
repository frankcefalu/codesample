<?php

namespace Drupal\carlylegirms_clickthrough\Form;

use Drupal;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\media\Entity\Media;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Symfony\Component\HttpFoundation\Request;

/**
 * Builds out the user agreement NDA Form.
 */
class NdaAgreementFormCaseStudies extends FormBase
{

  /**
   * Getter method for Form ID.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId()
  {
    return 'nda_agreement_form_case_studies';
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
  public function buildForm(array $form, FormStateInterface $form_state, $media_url = NULL)
  {
    if ($media_url) {
      $form['#media_url'] = [$media_url];
    }
    $ndaConfig = \Drupal::config('carlylegirms_clickthrough.adminsettings_case_studies');
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
      '#value' => $this->t('ACCEPT AND VIEW CASE STUDY'),
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
    // user_cookie_save(['carlylegirms' => 'carlyle_nda_case_study']);
    // $url = Url::fromRoute('<front>');
    // $form_state->setRedirectUrl($url);
  }

  /**
   * {@inheritdoc}
   */
  public function submitModalFormAjax(array &$form, FormStateInterface $form_state, Request $request)
  {
    $command = new CloseModalDialogCommand();
    $response = new AjaxResponse();
    $response->addCommand($command);
    if (isset($form['#media_url'])) {
      $media = Media::load(reset($form['#media_url']));
      $fid = $media->field_media_document->target_id;
      $file = File::load($fid);
      $url = file_url_transform_relative(file_create_url($file->getFileUri()));
      $command = new RedirectCommand($url);
      $response->addCommand($command);
      return $response;
    }
  }
}
