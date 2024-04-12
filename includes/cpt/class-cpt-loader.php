<?php
/**
 * CPT Loader class.
 *
 * Handles CPT functionality by loading classes that provide functionality for
 * individual CPTs.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\CPT;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * CPT Loader class.
 *
 * A class that loads CPT functionality.
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
	 * Activities CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Activities
	 */
	public $activities;

	/**
	 * Employees CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Employees
	 */
	public $employees;

	/**
	 * Households CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Households
	 */
	public $households;

	/**
	 * Organisations CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Organisations
	 */
	public $organisations;

	/**
	 * Parents CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Parents
	 */
	public $parents;

	/**
	 * Partners CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Partners
	 */
	public $partners;

	/**
	 * Participants CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Participants
	 */
	public $participants;

	/**
	 * Speakers CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Speakers
	 */
	public $speakers;

	/**
	 * Sponsors CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Sponsors
	 */
	public $sponsors;

	/**
	 * Students CPT object.
	 *
	 * @since 1.0.0
	 * @var CPT\Students
	 */
	public $students;

	/**
	 * Metabox template directory path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $metabox_path = 'assets/templates/wordpress/settings/metaboxes/';

	/**
	 * Active Custom Post Types setting key in Settings.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $key_post_types_enabled = 'cpts_enabled';

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
		$this->register_hooks();

		/**
		 * Fires when this class is loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/cpt/loaded' );

	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {

		// Separate callbacks into descriptive methods.
		$this->register_hooks_settings();

	}

	/**
	 * Registers "Settings" hooks.
	 *
	 * @since 1.0.0
	 */
	private function register_hooks_settings() {

		// Our objects require settings to have been initialised.
		add_filter( 'hay_cu/admin/settings/initialised', [ $this, 'setup_objects' ] );

		// Add our settings to default settings.
		add_filter( 'hay_cu/admin/settings/defaults', [ $this, 'settings_get_defaults' ] );

		// Add our metaboxes to the Site Settings screen.
		add_filter( 'hay_cu_settings/settings/page/meta_boxes/added', [ $this, 'settings_meta_boxes_append' ], 20, 2 );

		// Save data from Site Settings form submissions.
		add_action( 'hay_cu_settings/settings/form/save/before', [ $this, 'settings_meta_box_save' ] );

	}

	/**
	 * Instantiates objects.
	 *
	 * @since 1.0.0
	 */
	public function setup_objects() {

		// Get our active Custom Post Types.
		$cpts_enabled = $this->setting_cpts_enabled_get();

		// Enable chosen Custom Post Types.
		if ( ! empty( $cpts_enabled['activities'] ) ) {
			$this->activities = new Activities( $this );
		}
		if ( ! empty( $cpts_enabled['employees'] ) ) {
			$this->employees = new Employees( $this );
		}
		if ( ! empty( $cpts_enabled['households'] ) ) {
			$this->households = new Households( $this );
		}
		if ( ! empty( $cpts_enabled['organisations'] ) ) {
			$this->organisations = new Organisations( $this );
		}
		if ( ! empty( $cpts_enabled['parents'] ) ) {
			$this->parents = new Parents( $this );
		}
		if ( ! empty( $cpts_enabled['participants'] ) ) {
			$this->participants = new Participants( $this );
		}
		if ( ! empty( $cpts_enabled['partners'] ) ) {
			$this->partners = new Partners( $this );
		}
		if ( ! empty( $cpts_enabled['speakers'] ) ) {
			$this->speakers = new Speakers( $this );
		}
		if ( ! empty( $cpts_enabled['sponsors'] ) ) {
			$this->sponsors = new Sponsors( $this );
		}
		if ( ! empty( $cpts_enabled['students'] ) ) {
			$this->students = new Students( $this );
		}

		/**
		 * Fires when all CPT objects have been loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/cpt/objects/loaded' );

	}

	/**
	 * Appends our settings to the default core settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings The existing default settings.
	 * @return array $settings The modified default settings.
	 */
	public function settings_get_defaults( $settings ) {

		// Add our defaults.
		$settings[ $this->key_post_types_enabled ] = $this->setting_cpts_enabled_default_get();

		// --<
		return $settings;

	}

	/**
	 * Appends our metaboxes to the Settings screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $screen_id The Settings Screen ID.
	 * @param array  $data The array of metabox data.
	 */
	public function settings_meta_boxes_append( $screen_id, $data ) {

		// Define a handle for the following metabox.
		$handle = 'hay_cu_settings_cpts';

		// Add the metabox.
		add_meta_box(
			$handle,
			__( 'Custom Post Types', 'haystack-civicrm-utilities' ),
			[ $this, 'settings_meta_box_render' ], // Callback.
			$screen_id, // Screen ID.
			'normal', // Column: options are 'normal' and 'side'.
			'core', // Vertical placement: options are 'core', 'high', 'low'.
			$data
		);

	}

	/**
	 * Renders "Custom Post Types" meta box on Settings screen.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $unused Unused param.
	 * @param array $metabox Array containing id, title, callback, and args elements.
	 */
	public function settings_meta_box_render( $unused, $metabox ) {

		// Get our info and settings.
		$cpts_info    = $this->setting_cpts_info_get();
		$cpts_enabled = $this->setting_cpts_enabled_get();

		// Include template file.
		include HAYSTACK_CU_PATH . $this->metabox_path . 'metabox-settings-cpts.php';

	}

	/**
	 * Saves the data from the "Custom Post Types" metabox.
	 *
	 * Adds the data to the settings array. The settings are actually saved later.
	 *
	 * @see Admin\Page_Base::form_submitted()
	 *
	 * @since 1.0.0
	 */
	public function settings_meta_box_save() {

		// Find the data. Nonce has already been checked.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_types_enabled = filter_input( INPUT_POST, $this->key_post_types_enabled, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Sanitise array contents.
		if ( ! empty( $post_types_enabled ) ) {
			array_walk(
				$post_types_enabled,
				function( &$item ) {
					$item = sanitize_text_field( wp_unslash( $item ) );
				}
			);
		}

		// Apply to all Post Types.
		$cpts_enabled = [];
		$defaults     = $this->setting_cpts_enabled_default_get();
		foreach ( $defaults as $cpt => $enabled ) {
			if ( in_array( $cpt, $post_types_enabled, true ) ) {
				$cpts_enabled[ $cpt ] = true;
			} else {
				$cpts_enabled[ $cpt ] = false;
			}
		}

		// Set the setting.
		$this->setting_cpts_enabled_set( $cpts_enabled );

		// Flush the Rewrite Rules after registration.
		add_action( 'init', 'flush_rewrite_rules', 100 );

	}

	/**
	 * Gets the "Custom Post Types" info array.
	 *
	 * @since 1.0.0
	 *
	 * @return array $cpts_info The "Custom Post Types" info array.
	 */
	public function setting_cpts_info_get() {

		// Build info array.
		$cpts_info = [
			'activities'    => __( 'Activities', 'haystack-civicrm-utilities' ),
			'employees'     => __( 'Employees', 'haystack-civicrm-utilities' ),
			'households'    => __( 'Households', 'haystack-civicrm-utilities' ),
			'organisations' => __( 'Organisations', 'haystack-civicrm-utilities' ),
			'parents'       => __( 'Parents', 'haystack-civicrm-utilities' ),
			'participants'  => __( 'Participants', 'haystack-civicrm-utilities' ),
			'partners'      => __( 'Partners', 'haystack-civicrm-utilities' ),
			'speakers'      => __( 'Speakers', 'haystack-civicrm-utilities' ),
			'sponsors'      => __( 'Sponsors', 'haystack-civicrm-utilities' ),
			'students'      => __( 'Students', 'haystack-civicrm-utilities' ),
		];

		// --<
		return $cpts_info;

	}

	/**
	 * Gets the "Active Custom Post Types" setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array $cpts_enabled The setting if found, default otherwise.
	 */
	public function setting_cpts_enabled_default_get() {

		// Defaults to none active.
		$cpts_enabled = [
			'activities'    => false,
			'employees'     => false,
			'households'    => false,
			'organisations' => false,
			'parents'       => false,
			'participants'  => false,
			'partners'      => false,
			'speakers'      => false,
			'sponsors'      => false,
			'students'      => false,
		];

		// --<
		return $cpts_enabled;

	}

	/**
	 * Gets the "Active Custom Post Types" setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array $cpts_enabled The setting if found, default otherwise.
	 */
	public function setting_cpts_enabled_get() {

		// Get the setting.
		$cpts_enabled = $this->plugin->admin->setting_get( $this->key_post_types_enabled );

		// Return setting or default if empty.
		return ! empty( $cpts_enabled ) ? $cpts_enabled : $this->setting_cpts_enabled_default_get();

	}

	/**
	 * Sets the "Active Custom Post Types" setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $cpts_enabled The setting value.
	 */
	public function setting_cpts_enabled_set( $cpts_enabled ) {

		// Set the setting.
		$this->plugin->admin->setting_set( $this->key_post_types_enabled, $cpts_enabled );

	}

}
