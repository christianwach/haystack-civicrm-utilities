<?php
/**
 * Haystack CiviCRM Utilities
 *
 * Plugin Name:       Haystack CiviCRM Utilities
 * Description:       Provides additional utilities for working with CiviCRM.
 * Plugin URI:        https://github.com/christianwach/haystack-civicrm-utilities
 * GitHub Plugin URI: https://github.com/christianwach/haystack-civicrm-utilities
 * Version:           1.0.1a
 * Author:            Christian Wach
 * Author URI:        https://haystack.co.uk
 * Text Domain:       haystack-civicrm-utilities
 * Domain Path:       /languages
 *
 * @package Haystack_CU
 * @link    https://github.com/christianwach/haystack-civicrm-utilities
 * @license GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Version.
define( 'HAYSTACK_CU_VERSION', '1.0.1a' );

// Main plugin file.
if ( ! defined( 'HAYSTACK_CU_FILE' ) ) {
	define( 'HAYSTACK_CU_FILE', __FILE__ );
}

// Plugin basename.
if ( ! defined( 'HAYSTACK_CU_BASE' ) ) {
	define( 'HAYSTACK_CU_BASE', plugin_basename( HAYSTACK_CU_FILE ) );
}

// Plugin path.
if ( ! defined( 'HAYSTACK_CU_PATH' ) ) {
	define( 'HAYSTACK_CU_PATH', plugin_dir_path( HAYSTACK_CU_FILE ) );
}

// Source path.
if ( ! defined( 'HAYSTACK_CU_SRC' ) ) {
	define( 'HAYSTACK_CU_SRC', HAYSTACK_CU_PATH . 'includes' );
}

// Plugin URL.
if ( ! defined( 'HAYSTACK_CU_URL' ) ) {
	define( 'HAYSTACK_CU_URL', plugin_dir_url( HAYSTACK_CU_FILE ) );
}

/**
 * Gets a reference to this plugin.
 *
 * @since 1.0.0
 *
 * @return Haystack_CU\Plugin $plugin The plugin reference.
 */
function haystack_civicrm_utilities() {

	// Store plugin object in static variable.
	static $plugin = false;

	// Maybe bootstrap plugin.
	if ( false === $plugin ) {

		// Bootstrap autoloader.
		require_once trailingslashit( HAYSTACK_CU_SRC ) . 'class-autoloader.php';
		$namespace   = 'Haystack_CU';
		$source_path = HAYSTACK_CU_SRC;
		new Haystack_CU\Autoloader( $namespace, $source_path );

		// Bootstrap plugin.
		$plugin = new Haystack_CU\Plugin();

	}

	// --<
	return $plugin;

}

// Initialise plugin immediately.
haystack_civicrm_utilities();

/*
 * Uninstall uses the 'uninstall.php' method.
 *
 * @see https://developer.wordpress.org/reference/functions/register_uninstall_hook/
 */
