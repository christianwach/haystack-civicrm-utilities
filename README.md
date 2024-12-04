# Haystack CiviCRM Utilities

**Contributors:** [needle](https://profiles.wordpress.org/needle/)<br/>
**Donate link:** https://www.paypal.me/interactivist<br/>
**Tags:** civicrm, admin, utility, development<br/>
**Requires at least:** 4.9<br/>
**Tested up to:** 6.8<br/>
**Stable tag:** 1.0.1a<br/>
**License:** GPLv2 or later<br/>
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Provides utilities for working with CiviCRM.

## Description

*Haystack CiviCRM Utilities* is a WordPress plugin that provides utilities for working with CiviCRM.

### Usage

It is intended for use with the following plugins:

* [CiviCRM](https://civicrm.org/)
* [CiviCRM Admin Utilities](https://github.com/christianwach/civicrm-admin-utilities/)
* [CiviCRM Profile Sync](https://github.com/christianwach/civicrm-wp-profile-sync/)

## Installation

There are two ways to install from GitHub:

### ZIP Download

If you have downloaded this plugin as a ZIP file from the GitHub repository, do the following to install the plugin:

1. Unzip the .zip file and, if needed, rename the enclosing folder so that the plugin's files are located directly inside `/wp-content/plugins/haystack-civicrm-utilities`
2. Activate the plugin.
3. Configure the settings.
4. You're done.

### `git clone`

If you have cloned the code from GitHub, it is assumed that you know what you're doing.

## Setup

Despite having written this plugin for my own use when developing various plugins for CiviCRM in WordPress, it seems that others also find it useful. Below are some tips on how to set up this plugin for your environment.

### Custom Post Types

This plugin provides some Custom Post Types and Custom Taxonomies that are useful for working with CiviCRM Profile Sync - particularly when syncing CiviCRM data with WordPress via [Advanced Custom Fields](https://www.advancedcustomfields.com/) or [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) - whichever one floats your boat. You can enable the ones you want on this plugin's Settings Page.

### Shortcuts Menu

The CiviCRM Admin Utilities plugin provides a "CiviCRM Shortcuts" menu to link to resources that are mostly useful for CiviCRM users and administrators. Developers may prefer the set that this plugin provides - I find that they mean fewer clicks to get to the things I most need.

To enable the changes, set "Modify Shortcuts Menu" to "Yes" on this plugin's Settings Page. When this is done, individual users can choose if they want the modified Shortcuts Menu on their WordPress Profile page.
