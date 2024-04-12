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

		// Modify the CiviCRM Admin Utilities menu.
		add_action( 'civicrm_admin_utilities_menu_after', [ $this, 'menu_alter' ], 20, 3 );

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
