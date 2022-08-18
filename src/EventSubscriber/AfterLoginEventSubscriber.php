<?php

namespace Drupal\jma_customizations\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribe to KernelEvents::RESPONSE events.
 */
class AfterLoginEventSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The destination helper.
   *
   * @var \Drupal\Core\Routing\RedirectDestinationInterface
   */
  protected $destinationHelper;

  /**
   * The current path for the current request.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $currentPath;

  /**
   * The path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;

  /**
   * Constructs a AfterLoginEventSubscriber object.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Routing\RedirectDestinationInterface $destination_helper
   *   The destination helper.
   * @param \Drupal\Core\Path\CurrentPathStack $current_path
   *   The current path for the current request.
   * @param \Drupal\Core\Path\PathMatcherInterface $path_matcher
   *   The path matcher.
   */
  public function __construct(
    AccountInterface $account,
    ConfigFactoryInterface $config_factory,
    RedirectDestinationInterface $destination_helper,
    CurrentPathStack $current_path,
    PathMatcherInterface $path_matcher
  ) {
    $this->account = $account;
    $this->configFactory = $config_factory;
    $this->destinationHelper = $destination_helper;
    $this->currentPath = $current_path;
    $this->pathMatcher = $path_matcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Set priority to be -1 to fire after the destination parameter
    // is processed, so we can ignore if the destination is set to the same page
    // we're accessing.
    return [
      KernelEvents::RESPONSE => ['redirectAfterLoginEvent', -1],
    ];
  }

  /**
   * This method is called whenever the KernelEvents::RESPONSE event is
   * dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The Response event object.
   */
  public function redirectAfterLoginEvent(ResponseEvent $event) {
    $request = $event->getRequest();

    if ($request->get('form_id') === 'user_login_form' && $request->isMethod('POST')) {
      $current_url = $this->currentPath->getPath();

      // We use current raw path to check for destination equivalency so that
      // the returned url will be the form action and not the underlying
      // non-aliased path.
      $current_raw_path = $request->getPathInfo();
      $destination_path = $this->destinationHelper->get();
      $current_route = \Drupal::routeMatch()->getRouteName();

      // Only process if the destination is the same as the current url,
      // i.e. it's not set.
      // This ensures our code still runs when the user login form block
      // is placed on other pages.
      if ($current_raw_path === $destination_path) {
        // Redirect user on login, based on role (last in array).
        $current_user_roles = $this->account->getRoles();
       
        if (in_array('authorized_contact', $current_user_roles)) { 
          $event->setResponse(new RedirectResponse(URL::fromUserInput('/civicrm/service-listing-application')->toString()));
        }
      }
    }
  }

}
