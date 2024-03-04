<?php
/**
 * Admin abstract class.
 *
 * Handles common Admin functionality.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Admin abstract class.
 *
 * A class that handles common Admin functionality.
 *
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * Hook prefix.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $hook_prefix;

	/**
	 * Plugin version in the code.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $plugin_version_code;

	/**
	 * Plugin version in the database.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $plugin_version_db;

	/**
	 * The option that holds the plugin version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $option_version;

	/**
	 * The option that holds the plugin settings.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $option_settings;

	/**
	 * Upgrade flag.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $is_upgrade = false;

	/**
	 * Plugin settings.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Act when this plugin is activated.
		add_action( $this->hook_prefix . '/activated', [ $this, 'plugin_activate' ], 10 );

		// Act when this plugin is deactivated.
		add_action( $this->hook_prefix . '/deactivated', [ $this, 'plugin_deactivate' ], 10 );

		// Init when this plugin is loaded.
		add_action( $this->hook_prefix . '/loaded', [ $this, 'initialise' ] );

		// Init settings when all plugins are loaded.
		add_action( 'plugins_loaded', [ $this, 'settings_initialise' ] );

	}

	/**
	 * Initialises this object.
	 *
	 * @since 1.0.0
	 */
	public function initialise() {}

	// -----------------------------------------------------------------------------------

	/**
	 * Runs when core is activated.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide True if network-activated, false otherwise.
	 */
	public function plugin_activate( $network_wide = false ) {

		// Bail if plugin is network activated.
		if ( $network_wide ) {
			return;
		}

		// Init settings.
		$this->settings_initialise();

		// Save settings.
		$this->settings_save();

	}

	/**
	 * Runs when core is deactivated.
	 *
	 * Keep options when deactivating.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $network_wide True if network-activated, false otherwise.
	 */
	public function plugin_deactivate( $network_wide = false ) {

		// Init settings in case they are need elsewhere.
		$this->settings_initialise();

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Initialise settings.
	 *
	 * @since 4.0
	 */
	public function settings_initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && true === $done ) {
			return;
		}

		// Load installed plugin version.
		$this->plugin_version_db = $this->version_get();

		// Load settings array.
		$this->settings = $this->settings_get();

		// Store version if there has been a change.
		if ( $this->version_outdated() ) {
			$this->version_set( $this->plugin_version_code );
			$this->is_upgrade = true;
		}

		// Settings upgrade tasks.
		$this->settings_upgrade();

		/**
		 * Fires when settings have been initialised.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/admin/settings/initialised' );

		// We're done.
		$done = true;

	}

	/**
	 * Upgrades settings when required.
	 *
	 * @since 1.0.0
	 */
	public function settings_upgrade() {

		// Don't save by default.
		$save = false;

		/*
		// Some setting may not exist.
		if ( ! $this->setting_exists( 'some_setting' ) ) {
			$settings = $this->settings_get_defaults();
			$this->setting_set( 'some_setting', $settings['some_setting'] );
			$save = true;
		}
		*/

		/*
		// Things to always check on upgrade.
		if ( $this->is_upgrade ) {
			// Maybe save settings.
			//$save = true;
		}
		*/

		/**
		 * Filters the "Save settings" flag.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $save True if settings should be saved, false otherwise.
		 */
		$save = apply_filters( $this->hook_prefix . '/admin/settings/upgrade/save', $save );

		// Save settings if need be.
		if ( true === $save ) {
			$this->settings_save();
		}

	}

	/**
	 * Get default settings for this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return array $settings The default settings for this plugin.
	 */
	public function settings_get_defaults() {

		// Init return.
		$settings = [];

		/**
		 * Filter default settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings The array of default settings.
		 */
		$settings = apply_filters( $this->hook_prefix . '/admin/settings/defaults', $settings );

		// --<
		return $settings;

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Gets the settings array from a WordPress option.
	 *
	 * @since 1.0.0
	 *
	 * @return array $settings The array of settings if successful, or empty array otherwise.
	 */
	public function settings_get() {

		// Get the option.
		return $this->option_get( $this->option_settings, $this->settings_get_defaults() );

	}

	/**
	 * Saves the settings array in a WordPress option.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean $success True if successful, or false otherwise.
	 */
	public function settings_save() {

		// Set the option.
		return $this->option_set( $this->option_settings, $this->settings );

	}

	/**
	 * Deletes the settings WordPress option.
	 *
	 * @since 1.0.0
	 */
	public function settings_delete() {

		// Delete the option.
		$this->option_delete( $this->option_settings );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Checks whether a specified setting exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_name The name of the setting.
	 * @return bool Whether or not the setting exists.
	 */
	public function setting_exists( $setting_name ) {

		// Get existence of setting in array.
		return array_key_exists( $setting_name, $this->settings );

	}

	/**
	 * Returns a value for a specified setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_name The name of the setting.
	 * @param mixed  $default The default value if the setting does not exist.
	 * @return mixed The setting or the default.
	 */
	public function setting_get( $setting_name, $default = false ) {

		// Get setting.
		return ( array_key_exists( $setting_name, $this->settings ) ) ? $this->settings[ $setting_name ] : $default;

	}

	/**
	 * Sets a value for a specified setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_name The name of the setting.
	 * @param mixed  $value The value of the setting.
	 */
	public function setting_set( $setting_name, $value = '' ) {

		// Set setting.
		$this->settings[ $setting_name ] = $value;

	}

	/**
	 * Deletes a specified setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_name The name of the setting.
	 */
	public function setting_delete( $setting_name ) {

		// Unset setting.
		unset( $this->settings[ $setting_name ] );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Test existence of a specified option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option.
	 * @return bool $exists Whether or not the option exists.
	 */
	public function option_exists( $option_name ) {

		// Test by getting option with unlikely default.
		if ( 'fenfgehgefdfdjgrkj' === $this->option_get( $option_name, 'fenfgehgefdfdjgrkj' ) ) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * Return a value for a specified option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option.
	 * @param string $default The default value of the option if it has no value.
	 * @return mixed $value the value of the option.
	 */
	public function option_get( $option_name, $default = false ) {

		// Get option.
		$value = get_option( $option_name, $default );

		// --<
		return $value;

	}

	/**
	 * Set a value for a specified option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option.
	 * @param mixed  $value The value to set the option to.
	 * @return bool $success True if the value of the option was successfully updated.
	 */
	public function option_set( $option_name, $value = '' ) {

		// Update option.
		return update_option( $option_name, $value );

	}

	/**
	 * Delete a specified option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The name of the option.
	 * @return bool $success True if the option was successfully deleted.
	 */
	public function option_delete( $option_name ) {

		// Delete option.
		return delete_option( $option_name );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Gets the installed plugin version.
	 *
	 * @since 4.0
	 *
	 * @return string|bool $version The installed version, or false if none found.
	 */
	public function version_get() {

		// Get installed version cast as string.
		$version = (string) $this->option_get( $this->option_version );

		// Cast as boolean if not found.
		if ( empty( $version ) ) {
			$version = false;
		}

		// --<
		return $version;

	}

	/**
	 * Sets the plugin version.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version The version to save.
	 */
	public function version_set( $version ) {

		// Store new CommentPress Core version.
		$this->option_set( $this->option_version, $version );

	}

	/**
	 * Deletes the plugin version option.
	 *
	 * @since 1.0.0
	 */
	public function version_delete() {

		// Delete the version option.
		$this->option_delete( $this->option_version );

	}

	/**
	 * Checks for an outdated plugin version.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if outdated, false otherwise.
	 */
	public function version_outdated() {

		// Get installed version.
		$version = $this->version_get();

		// True if no version or we have an install and it's lower than this one.
		if ( empty( $version ) || version_compare( $this->plugin_version_code, $version, '>' ) ) {
			return true;
		}

		// Fallback.
		return false;

	}

}
