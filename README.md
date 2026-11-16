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

Staff sign in with Yukon SSO, the OpenID Connect client `generic` (redirect URL
`/openid-connect/generic`). Its credentials and endpoints differ for each
environment (DEV, UAT, PROD), so `config/sync` holds the placeholder
`SEELOCALSETTINGS` and `settings.php` reads the real values from these
environment variables:

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

Without all five, the client is turned off and the password login form shows.
With them, the login form shows only the Yukon SSO button; add `?showcore` to
`/user/login` for the password form.

Never enter real values in the client's settings form: they would be saved to
config and exported to git. The canary fails if `config/sync` holds anything
other than the placeholder.
