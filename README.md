# miniOrange OAuth Error Page

Joomla system plugin that replaces the miniOrange OAuth "User Auto-Creation Not Available" page with a built-in friendly access message.

This keeps SSO user auto-registration disabled while providing a friendlier page for people who do not already have a Joomla account. The page is rendered by the plugin, so it does not depend on a Joomla article or menu item being enabled.

## Installation

Install the plugin through Joomla's extension installer, then enable:

`System - miniOrange OAuth Error Redirect`

## Configuration

No plugin settings are required.

The Ramblers group name shown in the message is taken from Joomla's configured site name. If the site name is unavailable, the plugin falls back to "this Ramblers group".

## Behaviour

The plugin watches the miniOrange OAuth callback path:

`/api/index.php/v1/miniorangeoauth`

If the generated page contains miniOrange's auto-creation error text, it replaces the page with a built-in 403 access message.
