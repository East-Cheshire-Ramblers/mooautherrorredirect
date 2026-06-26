# miniOrange OAuth Error Redirect

Joomla system plugin that redirects the miniOrange OAuth "User Auto-Creation Not Available" page to a configured article or menu URL.

This keeps SSO user auto-registration disabled while providing a friendlier page for people who do not already have a Joomla account.

## Installation

Install the plugin through Joomla's extension installer, then enable:

`System - miniOrange OAuth Error Redirect`

## Configuration

Open the plugin settings and set `Redirect URL` to the Joomla article or menu URL that should be shown to unknown SSO users.

Examples:

- `/sso-access-denied`
- `/account-access-request`
- `https://example.org/sso-access-denied`

## Behaviour

The plugin watches the miniOrange OAuth callback path:

`/api/index.php/v1/miniorangeoauth`

If the generated page contains miniOrange's auto-creation error text, it replaces the page with a redirect to the configured URL.
