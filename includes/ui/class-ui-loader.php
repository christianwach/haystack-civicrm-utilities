<?php
/**
 * UI Loader class.
 *
 * Handles UI modification and enhancement by loading classes that provide that
 * functionality for aspects of the UI.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\UI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * UI Loader class.
 *
 * A class that loads UI functionality.
 *
 * @since 1.0.0
 */
class Loader {

	/**
	 * Plugin object.
	 *
	 * @since 1.0.0
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Menu object.
	 *
	 * @since 1.0.0
	 * @var UI\Menu
	 */
	public $menu;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param object $plugin The plugin object.
	 */
	public function __construct( $plugin ) {

		// Store reference to plugin.
		$this->plugin = $plugin;

		// Init when this plugin is loaded.
		add_action( 'hay_cu/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this object.
	 *
	 * @since 1.0.0
	 */
	public function initialise() {

		// Bootstrap class.
		$this->setup_objects();

		/**
		 * Fires when this class is loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/ui/loaded' );

	}

	/**
	 * Instantiates objects.
	 *
	 * @since 1.0.0
	 */
	public function setup_objects() {

		// Instantiate objects.
		$this->menu = new Menu( $this );

		/**
		 * Fires when all UI objects have been loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/ui/objects/loaded' );

	}

}
