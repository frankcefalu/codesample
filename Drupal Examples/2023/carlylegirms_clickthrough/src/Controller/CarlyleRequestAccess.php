<?php

namespace Drupal\carlylegirms_clickthrough\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Build the User request access page.
 *
 * @package Drupal\carlylegirms_clickthrough\Controller
 */
class CarlyleRequestAccess extends ControllerBase {

  /**
   * Retrieve the user register form.
   */
  protected function getRegisterForm() {
    $entity = $this->entityTypeManager()
      ->getStorage('user')
      ->create([]);
    $formObject = $this->entityTypeManager()
      ->getFormObject('user', 'register')
      ->setEntity($entity);
    return $this->formBuilder()->getForm($formObject);
  }

  /**
   * Build the user register page.
   *
   * @return array
   *   Returns the user register build array.
   */
  public function registerBuild() {
    // Import NDA admin form config.
    $registerForm = $this->getRegisterForm();

    return [
      '#theme' => 'carlyle_register',
      '#carlyle_register' => $registerForm,
    ];

  }

}
