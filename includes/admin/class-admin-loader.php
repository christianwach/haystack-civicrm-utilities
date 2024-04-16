<?php
/**
 * Admin Loader class.
 *
 * Handles Admin functionality.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Admin Loader class.
 *
 * A class that loads Admin functionality.
 *
 * @since 1.0.0
 */
class Loader extends Base {

	/**
	 * Plugin object.
	 *
	 * @since 1.0.0
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Hook prefix.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $hook_prefix = 'hay_cu';

	/**
	 * Settings Page object.
	 *
	 * @since 1.0.0
	 * @var Admin\Page_Settings
	 */
	private $page_settings;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Haystack_CU $plugin The plugin object.
	 */
	public function __construct( $plugin ) {

		// Store reference to plugin.
		$this->plugin = $plugin;

		// Assign plugin codebase version.
		$this->plugin_version_code = HAYSTACK_CU_VERSION;

		// Assign option names.
		$this->option_version  = $this->hook_prefix . '_version';
		$this->option_settings = $this->hook_prefix . '_settings';

		// Bootstrap parent.
		parent::__construct();

	}

	/**
	 * Initialises this object.
	 *
	 * @since 1.0.0
	 */
	public function initialise() {

		// Bootstrap class.
		$this->setup_objects();
		$this->register_hooks();

		/**
		 * Fires when this class is loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/admin/loaded' );

	}

	/**
	 * Instantiates objects.
	 *
	 * @since 1.0.0
	 */
	public function setup_objects() {

		$this->page_settings = new Page_Settings( $this );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {

	}

}
