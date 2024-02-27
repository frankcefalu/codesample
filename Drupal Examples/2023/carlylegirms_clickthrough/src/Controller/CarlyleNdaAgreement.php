<?php

namespace Drupal\carlylegirms_clickthrough\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Build the NDA agreement page.
 *
 * @package Drupal\carlylegirms_clickthrough\Controller
 */
class CarlyleNdaAgreement extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $controller = parent::create($container);
    $controller->configFactory = $container->get('config.factory');

    return $controller;
  }

  /**
   * Build the NDA agreement page.
   *
   * @return array
   *   Returns the NDA build array.
   */
  public function ndaAgreementBuild() {
    // Import NDA admin form config.
    $ndaConfig = $this->configFactory->get('carlylegirms_clickthrough.adminsettings');
    $ndaForm = $this->formBuilder()->getForm('Drupal\carlylegirms_clickthrough\Form\NdaAgreementForm');

    return [
      '#theme' => 'carlyle_nda',
      '#label' => $ndaConfig->get('nda_label_name'),
      '#nda_sub_heading' => $ndaConfig->get('nda_sub_heading'),
      '#nda_body' => [
        '#markup' => $ndaConfig->get('nda_message.value'),
      ],
      '#nda_agree' => $ndaForm,
    ];

  }

}
