<?php

namespace Drupal\yg_login\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Turns off password reset requests and the JSON login.
 *
 * Staff accounts have no passwords: they sign in with MyYukon. A reset would
 * let anyone who can read a staff mailbox give an account a password. This
 * also removes the login form's "Reset your password" tab.
 *
 * The JSON login (POST /user/login?_format=json, there whenever the
 * serialization module is on) accepts a name and password whatever the login
 * form shows, so it would be a password login that bypasses MyYukon.
 *
 * The one-time login routes (user.reset*) stay, because drush user:login,
 * the break-glass way in, uses them.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection): void {
    foreach (['user.pass', 'user.pass.http', 'user.login.http'] as $name) {
      if ($route = $collection->get($name)) {
        $route->setRequirements(['_access' => 'FALSE']);
      }
    }
  }

}
