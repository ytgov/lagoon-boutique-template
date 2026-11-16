<?php

namespace Drupal\yg_login\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Turns off the password reset request form.
 *
 * Staff accounts have no passwords: they sign in with MyYukon. A reset would
 * let anyone who can read a staff mailbox set a password and sign in with
 * the password form (/user/login?showcore), bypassing MyYukon. This also
 * removes the form's "Reset your password" tab.
 *
 * The one-time login routes (user.reset*) stay, because drush user:login,
 * the break-glass way in, uses them.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection): void {
    foreach (['user.pass', 'user.pass.http'] as $name) {
      if ($route = $collection->get($name)) {
        $route->setRequirements(['_access' => 'FALSE']);
      }
    }
  }

}
