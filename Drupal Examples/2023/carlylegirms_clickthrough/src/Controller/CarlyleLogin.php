<?php

namespace Drupal\carlylegirms_clickthrough\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Build the User login page.
 *
 * @package Drupal\carlylegirms_clickthrough\Controller
 */
class CarlyleLogin extends ControllerBase {

  /**
   * Drupal module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * Request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $controller = parent::create($container);
    $controller->moduleHandler = $container->get('module_handler');
    $controller->requestStack = $container->get('request_stack');

    return $controller;
  }

  /**
   * Checks if the simplaesamlphp_auth module is enabled.
   *
   * @return bool
   *   Returns true if module is enabled.
   */
  protected function samlEnabled() {
    $moduleHandler = $this->moduleHandler;
    if ($moduleHandler->moduleExists('simplesamlphp_auth')) {
      return TRUE;
    }
  }

  /**
   * Build the login page.
   *
   * @return array
   *   Returns the login build array.
   */
  public function loginBuild() {
    // If saml is not enabled on environment then we dont want to link to
    // saml login.
    $sso_options = [];

    if ($this->samlEnabled()) {
      $sso_route = 'simplesamlphp_auth.saml_login';

      $destination = $this->requestStack->getCurrentRequest()->query->get('destination');
      if (!empty($destination)) {
        $sso_options = ['query' => ['ReturnTo' => $destination]];
      }
    }
    else {
      $sso_route = 'carlyle.login';
    }

    // Import NDA admin form config.
    $loginForm = $this->formBuilder()
      ->getForm('Drupal\user\Form\UserLoginForm');

    CacheableMetadata::createFromRenderArray($loginForm)
      ->addCacheContexts(['url.query_args:destination'])
      ->applyTo($loginForm);

    return [
      '#theme' => 'carlyle_login',
      '#carlyle_login' => $loginForm,
      "#sso_route" => $sso_route,
      '#sso_options' => $sso_options,
    ];

  }

}
