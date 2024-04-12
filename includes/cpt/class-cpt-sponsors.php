<?php
/**
 * Sponsors Custom Post Type class.
 *
 * Handles providing a "Sponsors" Custom Post Type.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\CPT;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Sponsors Custom Post Type class.
 *
 * A class that encapsulates a "Sponsors" Custom Post Type.
 *
 * @since 1.0.0
 */
class Sponsors extends Base {

	/**
	 * Plugin object.
	 *
	 * @since 1.0.0
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Custom Post Type object.
	 *
	 * @since 1.0.0
	 * @var Haystack_CU_CPT
	 */
	public $cpt;

	/**
	 * Custom Post Type name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_name = 'sponsor';

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_rest_base = 'sponsors';

	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_name = 'sponsor-type';

	/**
	 * Taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_rest_base = 'sponsor-type';

	/**
	 * Free taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_name = 'sponsor-tag';

	/**
	 * Free taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_rest_base = 'sponsor-tag';

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 1.0.0
	 */
	public function post_type_create() {

		// Only call this once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Labels.
		$labels = [
			'name'               => __( 'Sponsors', 'haystack-civicrm-utilities' ),
			'singular_name'      => __( 'Sponsor', 'haystack-civicrm-utilities' ),
			'add_new'            => __( 'Add New', 'haystack-civicrm-utilities' ),
			'add_new_item'       => __( 'Add New Sponsor', 'haystack-civicrm-utilities' ),
			'edit_item'          => __( 'Edit Sponsor', 'haystack-civicrm-utilities' ),
			'new_item'           => __( 'New Sponsor', 'haystack-civicrm-utilities' ),
			'all_items'          => __( 'All Sponsors', 'haystack-civicrm-utilities' ),
			'view_item'          => __( 'View Sponsor', 'haystack-civicrm-utilities' ),
			'search_items'       => __( 'Search Sponsors', 'haystack-civicrm-utilities' ),
			'not_found'          => __( 'No matching Sponsor found', 'haystack-civicrm-utilities' ),
			'not_found_in_trash' => __( 'No Sponsors found in Trash', 'haystack-civicrm-utilities' ),
			'menu_name'          => __( 'Sponsors', 'haystack-civicrm-utilities' ),
		];

		// Rewrite.
		$rewrite = [
			'slug'       => 'sponsors',
			'with_front' => false,
		];

		// Supports.
		$supports = [
			'title',
			'editor',
			'excerpt',
			'thumbnail',
		];

		// Build args.
		$args = [
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-money-alt',
			'description'         => __( 'An sponsor post type', 'haystack-civicrm-utilities' ),
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'has_archive'         => false,
			'query_var'           => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => 40,
			'map_meta_cap'        => true,
			'rewrite'             => $rewrite,
			'supports'            => $supports,
			'show_in_rest'        => true,
			'rest_base'           => $this->post_type_rest_base,
		];

		// Set up the post type called "Sponsor".
		register_post_type( $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

	/**
	 * Override messages for a Custom Post Type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// Access relevant globals.
		global $post, $post_ID;

		// Define custom messages for our Custom Post Type.
		$messages[ $this->post_type_name ] = [

			// Unused - messages start at index 1.
			0  => '',

			// Item updated.
			1  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Sponsor updated. <a href="%s">View Sponsor</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2  => __( 'Custom field updated.', 'haystack-civicrm-utilities' ),
			3  => __( 'Custom field deleted.', 'haystack-civicrm-utilities' ),
			4  => __( 'Sponsor updated.', 'haystack-civicrm-utilities' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5  => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: The date and time of the revision. */
					__( 'Sponsor restored to revision from %s', 'haystack-civicrm-utilities' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Sponsor published. <a href="%s">View Sponsor</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7  => __( 'Sponsor saved.', 'haystack-civicrm-utilities' ),

			// Item submitted.
			8  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Sponsor submitted. <a target="_blank" href="%s">Preview Sponsor</a>', 'haystack-civicrm-utilities' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9  => sprintf(
				/* translators: 1: The date, 2: The permalink. */
				__( 'Sponsor scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Sponsor</a>', 'haystack-civicrm-utilities' ),
				/* translators: Publish box date format - see https://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'haystack-civicrm-utilities' ), strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Sponsor draft updated. <a target="_blank" href="%s">Preview Sponsor</a>', 'haystack-civicrm-utilities' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

		];

		// --<
		return $messages;

	}

	/**
	 * Override the "Add title" label.
	 *
	 * @since 1.0.0
	 *
	 * @param str $title The existing title - usually "Add title".
	 * @return str $title The modified title.
	 */
	public function post_type_title( $title ) {

		// Bail if not our post type.
		if ( get_post_type() !== $this->post_type_name ) {
			return $title;
		}

		// Overwrite with our string.
		$title = __( 'Add the name of the Sponsor', 'haystack-civicrm-utilities' );

		// --<
		return $title;

	}

	/**
	 * Create our Custom Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function taxonomy_create() {

		// Only register once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Labels.
		$labels = [
			'name'              => _x( 'Sponsor Types', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Sponsor Type', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Sponsor Types', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Sponsor Types', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Sponsor Type', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Sponsor Type:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Sponsor Type', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Sponsor Type', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Sponsor Type', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Sponsor Type Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Sponsor Types', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Sponsor Types found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'sponsor-types',
		];

		// Arguments.
		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'rewrite'           => $rewrite,
			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui'           => true,
			// REST setup.
			'show_in_rest'      => true,
			'rest_base'         => $this->taxonomy_rest_base,
		];

		// Register a taxonomy for this CPT.
		register_taxonomy( $this->taxonomy_name, $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

	/**
	 * Create our free Custom Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function tag_create() {

		// Only register once.
		static $registered;
		if ( $registered ) {
			return;
		}

		// Labels.
		$labels = [
			'name'              => _x( 'Sponsor Tags', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Sponsor Tag', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Sponsor Tags', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Sponsor Tags', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Sponsor Tag', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Sponsor Tag:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Sponsor Tag', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Sponsor Tag', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Sponsor Tag', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Sponsor Tag Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Sponsor Tags', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Sponsor Tags found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'sponsor-tags',
		];

		// Arguments.
		$args = [
			'hierarchical'      => false,
			'labels'            => $labels,
			'rewrite'           => $rewrite,
			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui'           => true,
			// REST setup.
			'show_in_rest'      => true,
			'rest_base'         => $this->tag_rest_base,
		];

		// Register a taxonomy for this CPT.
		register_taxonomy( $this->tag_name, $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

}
