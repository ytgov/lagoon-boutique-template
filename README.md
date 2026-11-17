# Government of Yukon Lagoon Drupal 11 Template

## Introduction

This template is the starting point for all new "boutique" Drupal CMS websites
hosted by ICT - eServices.
A boutique website is one that has received approval to exist separately from
[yukon.ca](https://yukon.ca) or that is too large or technically complex to be
implemented as a campaign site within yukon.ca's CMS.

Once a new repository is created from this template, the website built from that
repo can run:

- locally in a development containerized environment managed by `ddev`,
- hosted atop the eServices Platform in either a TEST or PROD environment, or
- hosted atop the amazee.io Lagoon Platform under eServices' account.

## How to Contribute

See [CONTRIBUTING.md](CONTRIBUTING.md)

## Licence

Unless otherwise noted, the source code of this project is distributed under the
[MIT License](LICENSE).

## Requirements

- ddev installed
- Lagoon CLI installed and configured

## Installation

### Install new site from template in local development environment

Ask eServices to provide a Drupal instance name for this project.
Instances made from this template will have names beginning with `lagoon-` and
contain only alphanumeric or hyphen characters.

1. Replace "lagoon-boutique-template" with the desired Drupal instance name,
   particularly in `.ddev/config.yaml` and `composer.json`.
2. Launch the development environment: `ddev start`
3. Visit the new site's Drupal installation page at a URL like
   `https://lagoon-instance-name.ddev.site`
4. Install the site with the following options:
   - Choose language: English
   - Installation profile: Minimal
   - Configure site: as desired but site settings will be overridden shortly.
     The site email address should be `eservices@yukon.ca` unless advised otherwise by eServices.
5. Get the installation's site UUID: `ddev drush cget system.site uuid`
6. Replace the `uuid` value in `config/sync/system.site.yml` with the UUID from
   the previous step.
   Change the `name` value as desired.
   _Once you've created a specific project from this template, you'll need to override this new UUID value in
   `config/sync/system.site.yml`on any new DB-less installations of the project using
   `ddev drush cset system.site uuid <new UUID>`._
7. Import the template's default configuration: `ddev drush cim -y`
8. Genuflect to the Drupal God: `ddev drush cr`
9. Add the user created in step #4 to the Administrator role.
10. Verify there any errors shown on the Drupal Status report (`/admin/reports/status`) are within reason.

## Yukon SSO (OpenID Connect)

Staff sign in with MyYukon through Yukon SSO, the OpenID Connect client
`generic` (redirect URL `/openid-connect/generic`). Its credentials and
endpoints differ for each environment (DEV, UAT, PROD), so `config/sync` holds
the placeholder `SEELOCALSETTINGS` and `settings.php` reads the real values
from these environment variables:

| Variable | Client setting |
|---|---|
| `OIDC_CLIENT_ID` | Client ID |
| `OIDC_CLIENT_SECRET` | Client secret |
| `OIDC_AUTHORIZATION_ENDPOINT` | Authorization endpoint |
| `OIDC_TOKEN_ENDPOINT` | Token endpoint |
| `OIDC_USERINFO_ENDPOINT` | UserInfo endpoint |

- On Lagoon, set them as runtime environment variables for each environment.
- On other servers, copy `web/sites/default/example.settings.local.php` to
  `web/sites/default/settings.local.php` (git ignores it) and fill in the
  values there.

Without all five, the client is turned off and the password login form shows
(`settings.php` overrides the button display to "Hidden"), as in DDEV and the
canary. With them, the login page shows only the "Log in with MyYukon" button,
and there's no password login at all (see below).

Never enter real values in the client's settings form: they would be saved to
config and exported to git. The canary fails if `config/sync` holds anything
other than the placeholder.

### Login standards

- **No prompt.** The client sends no `prompt`. MyYukon passes it on to Entra
  ID, which rejects it combined with the one MyYukon adds ("AADSTS90023:
  Unsupported 'prompt' value"). openid_connect 3.x gives new clients `login`,
  so check this after adding a client or upgrading from 1.x. The status report
  warns about it; that's expected. The canary fails if a client has a prompt.
- **No self-registration.** *Override registration settings* is off, so only
  existing accounts can sign in. An administrator creates each account; it's
  linked to MyYukon on its first sign-in by email (*Automatically connect
  existing users*, on). That's safe only if the site's MyYukon sign-in
  comes from Entra ID, which owns staff email addresses: confirm it with the
  MyYukon team for each site.
- **No password login.** "OpenID buttons display in user login form" is
  "Force replace" (`force_replace`), so the password form doesn't show, not
  even with `?showcore`. A password login bypasses MyYukon's sign-in checks,
  and removing someone's MyYukon access wouldn't remove it.
- **No password reset or JSON login.** The `yg_login` module turns off
  `/user/password` (the canary checks it returns 403) and the JSON login
  (`POST /user/login?_format=json`, which accepts a password whatever the
  login page shows; it exists whenever the serialization module is on), and
  removes the login page's tabs. Staff have no passwords, and a reset would
  let anyone who can read a staff mailbox give the account one.
- **Break-glass access** is `drush user:login <name>` from a shell on the
  server, for a named administrator who normally signs in with MyYukon. It
  doesn't work for blocked accounts. Once named administrators can sign in,
  block the installer's account (`drush user:block admin`) on each server.
- **Button text.** `yg_login` labels the button "Log in with MyYukon". Its
  French version is an interface translation of that string, set on each
  site.
