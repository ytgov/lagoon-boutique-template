<?php

/**
 * @file
 * Example of this server's settings.local.php (not for Lagoon).
 *
 * Copy to settings.local.php in this directory, which git ignores, and fill
 * in this environment's values. settings.php includes it last. Keep it
 * readable only by the web server and the deploy account (mode 640).
 */

// Yukon SSO (OpenID Connect "generic" client). DEV, UAT and PROD each have
// their own client and endpoints; eServices provides them. settings.php reads
// these variables. Leave any of them empty to turn Yukon SSO off and keep the
// password login form.
putenv('OIDC_CLIENT_ID=');
putenv('OIDC_CLIENT_SECRET=');
putenv('OIDC_AUTHORIZATION_ENDPOINT=');
putenv('OIDC_TOKEN_ENDPOINT=');
putenv('OIDC_USERINFO_ENDPOINT=');
