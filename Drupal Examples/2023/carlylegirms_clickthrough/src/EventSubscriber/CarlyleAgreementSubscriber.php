<?php

namespace Drupal\carlylegirms_clickthrough\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\carlylegirms_clickthrough\EventSubscriber
 */
class CarlyleAgreementSubscriber implements EventSubscriberInterface {

  /**
   * The user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $accountProxy;

  /**
   * CarlyleAgreementSubscriber constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account_proxy
   *   Construct the user account.
   */
  public function __construct(AccountProxyInterface $account_proxy) {
    $this->accountProxy = $account_proxy;
  }

  /**
   * This method is called whenever the kernel.request event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The response event.
   */
  public function kernelRequest(RequestEvent $event) {
    if ($this->accountProxy->isAnonymous()) {
      // Only redirect logged-in users.
      return;
    }

    if (!$this->accountProxy->hasPermission('access content')) {
      // This is a user with no permissions to access content. Do nothing so an
      // Access denied page will appear. This shouldn't happen since everyone
      // should have at least the "member" role.
      return;
    }

    $request = $event->getRequest();

    $cookie_value = $request->cookies->get('Drupal_visitor_carlylegirms');
    $path = $request->getPathInfo();
    // Check if user is logging in from a generated link.
    $generated_login = str_contains($path, '/user/reset/') ? TRUE : FALSE;
    $allowedPaths = [
      '/user/agreement',
      '/carlyle/login',
      '/carlyle/register',
      '/saml_login',
      '/user/logout',
      '/user/password',
      '/user/reset',
      '/user',
      '/admin/config/clickthrough/NdaAgreementForm',
    ];

    // If the user does not have the carlyle_nda cookie then redirect to nda
    // agreement.
    // if ($generated_login === FALSE && $cookie_value != 'carlyle_nda' && !in_array($path, $allowedPaths) && !str_contains($path, '/user')) {
    //   // Create the destination URL.
    //   $url = Url::fromRoute('clickthrough.nda_agreement_form', [], [
    //     // Redirect to webinar after NDA form.
    //     'query' => [
    //       // Use this as an opportunity to get rid of the check_logged_in
    //       // variable to avoid another redirect later.
    //       'destination' => $this->getUriNoLoginCheck($request),
    //     ],
    //   ]);

    //   // Create redirect:
    //   $response = new RedirectResponse($url->toString());
    //   $event->setResponse($response);
    //   return;
    // }

    // If user is already logged in and goes to /carlyle/register then redirect
    // to /user.
    if ($path == '/carlyle/register') {
      $url = Url::fromRoute('user.page')->toString();

      // Create redirect:
      $response = new RedirectResponse($url);
      $event->setResponse($response);
    }

    if ($request->query->has('check_logged_in')) {
      // We're logged in but we have the check_logged_in query string variable.
      // This normally only appears on the /user page immediately after login
      // but we are probably redirecting to another page. Redirect to get rid
      // of this distraction on the URL. This is quite noticeable on big pages
      // with a menu of anchor links.
      // Added in Drupal 9.3 https://www.drupal.org/project/drupal/issues/2946.
      $response = new RedirectResponse($this->getUriNoLoginCheck($request));
      $event->setResponse($response);
    }
  }

  /**
   * Rebuild the uri without the check_logged_in query string variable.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request.
   *
   * @return string
   *   Uri.
   */
  private function getUriNoLoginCheck(Request $request) {
    if (!$request->query->has('check_logged_in')) {
      return $request->getRequestUri();
    }

    $query = $request->query;
    $query->remove('check_logged_in');

    return Url::fromUri('internal:' . $request->getPathInfo(), [
      'query' => $query,
    ])->toString();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[KernelEvents::REQUEST][] = ['kernelRequest', 28];
    return $events;
  }

}
