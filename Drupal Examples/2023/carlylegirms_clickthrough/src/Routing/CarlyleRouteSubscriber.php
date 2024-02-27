<?php

namespace Drupal\carlylegirms_clickthrough\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class CarlyleRouteSubscriber extends RouteSubscriberBase {

  /**
   * Alter default user login/register routes.
   *
   * @inheritDoc
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change path '/user/login' to '/carlyle/login'.
    if ($route = $collection->get('user.login')) {
      $route->setPath('/carlyle/login');
    }
    // Change path '/user/register' to '/carlyle/register'.
    if ($route = $collection->get('user.register')) {
      $route->setPath('/carlyle/register');
    }

  }

}
