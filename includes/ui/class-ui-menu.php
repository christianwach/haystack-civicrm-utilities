<?php
/**
 * UI Menu class.
 *
 * Handles modification of the CiviCRM Admin Utilities "Shortcuts Menu".
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\UI;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * UI Menu class.
 *
 * A class that modifies the CiviCRM Admin Utilities "Shortcuts Menu".
 *
 * @since 1.0.0
 */
class Menu {

	/**
	 * Plugin object.
	 *
	 * @since 1.0.0
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * User Interface loader object.
	 *
	 * @since 1.0.0
	 * @var UI\Loader
	 */
	public $ui;

	/**
	 * Metabox template directory path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $metabox_path = 'assets/templates/wordpress/settings/metaboxes/';

	/**
	 * UI Menu Components setting key in Settings.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $key_menu_enabled = 'menu_enabled';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param object $ui The UI object.
	 */
	public function __construct( $ui ) {

		// Store references to objects.
		$this->plugin = $ui->plugin;
		$this->ui     = $ui;

		// Init when this plugin is loaded.
		add_action( 'hay_cu/ui/loaded', [ $this, 'initialise' ] );

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
		do_action( 'hay_cu/ui/menu/loaded' );

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

		// Setting up this class requires settings to have been initialised.
		add_filter( 'hay_cu/admin/settings/initialised', [ $this, 'bootstrap_functionality' ] );

		// Add our settings to default settings.
		add_filter( 'hay_cu/admin/settings/defaults', [ $this, 'settings_get_defaults' ] );

		// Add our metaboxes to the Site Settings screen.
		add_filter( 'hay_cu_settings/settings/page/meta_boxes/added', [ $this, 'settings_meta_boxes_append' ], 20, 2 );

		// Save data from Site Settings form submissions.
		add_action( 'hay_cu_settings/settings/form/save/before', [ $this, 'settings_meta_box_save' ] );

	}

	/**
	 * Bootstraps the functionality in this class.
	 *
	 * @since 1.0.0
	 */
	public function bootstrap_functionality() {

		// Modify the CiviCRM Admin Utilities menu.
		add_action( 'civicrm_admin_utilities_menu_after', [ $this, 'menu_alter' ], 20, 3 );

		/**
		 * Fires when all UI objects have been loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'hay_cu/ui/bootstrapped' );

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
		$settings[ $this->key_menu_enabled ] = $this->setting_menu_enabled_default_get();

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
		$handle = 'hay_cu_settings_uis';

		// Add the metabox.
		add_meta_box(
			$handle,
			__( 'User Interface', 'haystack-civicrm-utilities' ),
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

		// Get our settings.
		$menu_enabled = $this->setting_menu_enabled_get();

		// Include template file.
		include HAYSTACK_CU_PATH . $this->metabox_path . 'metabox-settings-ui-menu.php';

	}

	/**
	 * Saves the data from the "User Interface" metabox.
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
		$menu_enabled = filter_input( INPUT_POST, $this->key_menu_enabled, FILTER_SANITIZE_SPECIAL_CHARS );

		// Sanitise data.
		$menu_enabled = sanitize_text_field( wp_unslash( $menu_enabled ) );

		// Set the setting.
		$this->setting_menu_enabled_set( $menu_enabled );

	}

	/**
	 * Gets the default "Modify Shortcuts Menu" setting.
	 *
	 * @since 1.0.0
	 *
	 * @return string $menu_enabled The default setting value.
	 */
	public function setting_menu_enabled_default_get() {

		// Defaults to not active.
		$menu_enabled = 'no';

		// --<
		return $menu_enabled;

	}

	/**
	 * Gets the "Active Custom Post Types" setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array $menu_enabled The setting if found, default otherwise.
	 */
	public function setting_menu_enabled_get() {

		// Get the setting.
		$menu_enabled = $this->plugin->admin->setting_get( $this->key_menu_enabled );

		// Return setting or default if empty.
		return ! empty( $menu_enabled ) ? $menu_enabled : $this->setting_menu_enabled_default_get();

	}

	/**
	 * Sets the "Active Custom Post Types" setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $menu_enabled The setting value.
	 */
	public function setting_menu_enabled_set( $menu_enabled ) {

		// Set the setting.
		$this->plugin->admin->setting_set( $this->key_menu_enabled, $menu_enabled );

	}

	/**
	 * Modifies the CiviCRM Admin Utilities Shortcuts Menu.
	 *
	 * @since 1.0.0
	 *
	 * @param str          $id The menu parent ID.
	 * @param array        $components The active CiviCRM Conponents.
	 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance, passed by reference.
	 */
	public function menu_alter( $id, $components, $wp_admin_bar ) {

		// Bail if not modifying the menu.
		$menu_enabled = $this->setting_menu_enabled_get();
		if ( empty( $menu_enabled ) || 'no' === $menu_enabled ) {
			return;
		}

		// Contributions.
		if ( array_key_exists( 'CiviContribute', $components ) ) {
			if ( civicrm_au()->single->check_permission( 'access CiviContribute' ) ) {
				$wp_admin_bar->add_node(
					[
						'id'     => 'cau-4',
						'parent' => $id,
						'title'  => __( 'All Contribution Pages', 'haystack-civicrm-utilities' ),
						'href'   => civicrm_au()->single->get_link( 'civicrm/admin/contribute', 'reset=1' ),
					]
				);
			}
		}

		// Membership.
		if ( array_key_exists( 'CiviMember', $components ) ) {
			if ( civicrm_au()->single->check_permission( 'access CiviMember' ) ) {
				$wp_admin_bar->add_node(
					[
						'id'     => 'cau-5',
						'parent' => $id,
						'title'  => __( 'All Memberships', 'haystack-civicrm-utilities' ),
						'href'   => civicrm_au()->single->get_link( 'civicrm/member/search', 'force=true&reset=1' ),
					]
				);
			}
		}

		// Events.
		if ( array_key_exists( 'CiviEvent', $components ) ) {
			if ( civicrm_au()->single->check_permission( 'access CiviEvent' ) ) {
				$wp_admin_bar->add_node(
					[
						'id'     => 'cau-6',
						'parent' => $id,
						'title'  => __( 'All Events', 'haystack-civicrm-utilities' ),
						'href'   => civicrm_au()->single->get_link( 'civicrm/event/manage', 'reset=1' ),
					]
				);
			}
		}

		// Mailings.
		if ( array_key_exists( 'CiviMail', $components ) ) {
			if ( civicrm_au()->single->check_permission( 'access CiviMail' ) ) {
				$wp_admin_bar->add_node(
					[
						'id'     => 'cau-7',
						'parent' => $id,
						'title'  => __( 'All Mailings', 'haystack-civicrm-utilities' ),
						'href'   => civicrm_au()->single->get_link( 'civicrm/mailing/browse/scheduled', 'reset=1&scheduled=true' ),
					]
				);
			}
		}

		// I don't need the CiviCRM Admin Utilities link.
		$wp_admin_bar->remove_node( 'cau-11' );

		// Add WordPress Permissions.
		if ( civicrm_au()->single->check_permission( 'access CiviCRM' ) ) {
			$wp_admin_bar->add_node(
				[
					'id'     => 'hay-wp',
					'parent' => $id,
					'title'  => __( 'WordPress Permissions', 'haystack-civicrm-utilities' ),
					'href'   => civicrm_au()->single->get_link( 'civicrm/admin/access/wp-permissions', 'reset=1' ),
				]
			);
		}

		// Add API Explorer v3.
		if ( civicrm_au()->single->check_permission( 'access CiviCRM' ) ) {
			$wp_admin_bar->add_node(
				[
					'id'     => 'hay-api-3',
					'parent' => $id,
					'title'  => __( 'API Explorer v3', 'haystack-civicrm-utilities' ),
					'href'   => civicrm_au()->single->get_link( 'civicrm/api3', '' ),
				]
			);
		}

		// Add API Explorer v4.
		if ( civicrm_au()->single->check_permission( 'access CiviCRM' ) ) {
			$wp_admin_bar->add_node(
				[
					'id'     => 'hay-api-4',
					'parent' => $id,
					'title'  => __( 'API Explorer v4', 'haystack-civicrm-utilities' ),
					'href'   => civicrm_au()->single->get_link( 'civicrm/api4', '' ),
				]
			);
		}

	}

}
