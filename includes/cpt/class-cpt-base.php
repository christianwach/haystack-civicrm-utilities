<?php
/**
 * Abstract Custom Post Type class.
 *
 * Handles common Custom Post Type functionality.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\CPT;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Custom Post Type class.
 *
 * A class that encapsulates common Custom Post Type functionality.
 *
 * @since 1.0.0
 */
abstract class Base {

	/**
	 * Custom Post Type name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_name;

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_rest_base;

	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_name;

	/**
	 * Taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_rest_base;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Init when this plugin is loaded.
		add_action( 'hay_cu/cpt/objects/loaded', [ $this, 'register_hooks' ] );

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {

		// Activation and deactivation.
		add_action( 'hay_cu/activate', [ $this, 'activate' ] );
		add_action( 'hay_cu/deactivate', [ $this, 'deactivate' ] );

		// Always create post type.
		add_action( 'init', [ $this, 'post_type_create' ] );

		// Make sure our feedback is appropriate.
		add_filter( 'post_updated_messages', [ $this, 'post_type_messages' ] );

		// Make sure our UI text is appropriate.
		add_filter( 'enter_title_here', [ $this, 'post_type_title' ] );

		// Maybe create primary taxonomy.
		if ( ! empty( $this->taxonomy_name ) ) {
			add_action( 'init', [ $this, 'taxonomy_create' ] );
			add_filter( 'wp_terms_checklist_args', [ $this, 'taxonomy_fix_metabox' ], 10, 2 );
			add_action( 'restrict_manage_posts', [ $this, 'taxonomy_filter_post_type' ] );
		}

		// Maybe create secondary taxonomy.
		if ( ! empty( $this->taxonomy_alt_name ) ) {
			add_action( 'init', [ $this, 'taxonomy_alt_create' ] );
			add_filter( 'wp_terms_checklist_args', [ $this, 'taxonomy_alt_fix_metabox' ], 10, 2 );
			add_action( 'restrict_manage_posts', [ $this, 'taxonomy_alt_filter_post_type' ] );
		}

		// Maybe create free taxonomy.
		if ( ! empty( $this->tag_name ) ) {
			add_action( 'init', [ $this, 'tag_create' ] );
			add_action( 'restrict_manage_posts', [ $this, 'tag_filter_post_type' ] );
		}

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		// Pass through.
		$this->post_type_create();
		$this->taxonomy_create();
		$this->taxonomy_alt_create();
		$this->tag_create();

		// Go ahead and flush.
		flush_rewrite_rules();

	}

	/**
	 * Actions to perform on plugin deactivation (NOT deletion).
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {

		// Flush rules to reset.
		flush_rewrite_rules();

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 1.0.0
	 */
	abstract public function post_type_create();

	/**
	 * Override messages for a Custom Post Type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	abstract public function post_type_messages( $messages );

	/**
	 * Override the "Add title" label.
	 *
	 * @since 1.0.0
	 *
	 * @param str $title The existing title - usually "Add title".
	 * @return str $title The modified title.
	 */
	public function post_type_title( $title ) {
		return $title;
	}

	// -----------------------------------------------------------------------------------

	/**
	 * Create our Custom Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function taxonomy_create() {}

	/**
	 * Fix the Custom Taxonomy metabox.
	 *
	 * @see https://core.trac.wordpress.org/ticket/10982
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The existing arguments.
	 * @param int   $post_id The WordPress post ID.
	 */
	public function taxonomy_fix_metabox( $args, $post_id ) {

		// If rendering metabox for our taxonomy.
		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] === $this->taxonomy_name ) {

			// Setting 'checked_ontop' to false seems to fix this.
			$args['checked_ontop'] = false;

		}

		// --<
		return $args;

	}

	/**
	 * Add a filter for this Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 1.0.0
	 */
	public function taxonomy_filter_post_type() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow !== $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->taxonomy_name );

		// Build args.
		$args = [
			/* translators: %s: The plural name of the taxonomy terms. */
			'show_option_all' => sprintf( __( 'Show All %s', 'haystack-civicrm-utilities' ), $taxonomy->label ),
			'taxonomy'        => $this->taxonomy_name,
			'name'            => $this->taxonomy_name,
			'orderby'         => 'name',
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			'selected'        => isset( $_GET[ $this->taxonomy_name ] ) ? wp_unslash( $_GET[ $this->taxonomy_name ] ) : '',
			'show_count'      => true,
			'hide_empty'      => true,
			'value_field'     => 'slug',
			'hierarchical'    => 1,
		];

		// Show a dropdown.
		wp_dropdown_categories( $args );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Create our alternative Custom Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function taxonomy_alt_create() {}

	/**
	 * Fix the alternative Custom Taxonomy metabox.
	 *
	 * @see https://core.trac.wordpress.org/ticket/10982
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The existing arguments.
	 * @param int   $post_id The WordPress post ID.
	 */
	public function taxonomy_alt_fix_metabox( $args, $post_id ) {

		// If rendering metabox for our taxonomy.
		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] === $this->taxonomy_alt_name ) {

			// Setting 'checked_ontop' to false seems to fix this.
			$args['checked_ontop'] = false;

		}

		// --<
		return $args;

	}

	/**
	 * Add a filter for the alternative Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 1.0.0
	 */
	public function taxonomy_alt_filter_post_type() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow !== $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->taxonomy_alt_name );

		// Build args.
		$args = [
			/* translators: %s: The plural name of the taxonomy terms. */
			'show_option_all' => sprintf( __( 'Show All %s', 'haystack-civicrm-utilities' ), $taxonomy->label ),
			'taxonomy'        => $this->taxonomy_alt_name,
			'name'            => $this->taxonomy_alt_name,
			'orderby'         => 'name',
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			'selected'        => isset( $_GET[ $this->taxonomy_alt_name ] ) ? wp_unslash( $_GET[ $this->taxonomy_alt_name ] ) : '',
			'show_count'      => true,
			'hide_empty'      => true,
			'value_field'     => 'slug',
			'hierarchical'    => 1,
		];

		// Show a dropdown.
		wp_dropdown_categories( $args );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Create our free Custom Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function tag_create() {}

	/**
	 * Add a filter for the free Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 1.0.0
	 */
	public function tag_filter_post_type() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow !== $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->tag_name );

		// Build args.
		$args = [
			/* translators: %s: The plural name of the taxonomy terms. */
			'show_option_all' => sprintf( __( 'Show All %s', 'haystack-civicrm-utilities' ), $taxonomy->label ),
			'taxonomy'        => $this->tag_name,
			'name'            => $this->tag_name,
			'orderby'         => 'name',
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended
			'selected'        => isset( $_GET[ $this->tag_name ] ) ? wp_unslash( $_GET[ $this->tag_name ] ) : '',
			'show_count'      => true,
			'hide_empty'      => true,
			'value_field'     => 'slug',
			'hierarchical'    => 1,
		];

		// Show a dropdown.
		wp_dropdown_categories( $args );

	}

}
