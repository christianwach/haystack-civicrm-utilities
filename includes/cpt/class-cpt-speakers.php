<?php
/**
 * Speakers Custom Post Type class.
 *
 * Handles providing a "Speakers" Custom Post Type.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\CPT;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Speakers Custom Post Type class.
 *
 * A class that encapsulates a "Speakers" Custom Post Type.
 *
 * @since 1.0.0
 */
class Speakers extends Base {

	/**
	 * Custom Post Type name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_name = 'speaker';

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_rest_base = 'speakers';

	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_name = 'speaker-type';

	/**
	 * Taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_rest_base = 'speaker-type';

	/**
	 * Free taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_name = 'speaker-tag';

	/**
	 * Free taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_rest_base = 'speaker-tag';

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
			'name'               => __( 'Speakers', 'haystack-civicrm-utilities' ),
			'singular_name'      => __( 'Speaker', 'haystack-civicrm-utilities' ),
			'add_new'            => __( 'Add New', 'haystack-civicrm-utilities' ),
			'add_new_item'       => __( 'Add New Speaker', 'haystack-civicrm-utilities' ),
			'edit_item'          => __( 'Edit Speaker', 'haystack-civicrm-utilities' ),
			'new_item'           => __( 'New Speaker', 'haystack-civicrm-utilities' ),
			'all_items'          => __( 'All Speakers', 'haystack-civicrm-utilities' ),
			'view_item'          => __( 'View Speaker', 'haystack-civicrm-utilities' ),
			'search_items'       => __( 'Search Speakers', 'haystack-civicrm-utilities' ),
			'not_found'          => __( 'No matching Speaker found', 'haystack-civicrm-utilities' ),
			'not_found_in_trash' => __( 'No Speakers found in Trash', 'haystack-civicrm-utilities' ),
			'menu_name'          => __( 'Speakers', 'haystack-civicrm-utilities' ),
		];

		// Rewrite.
		$rewrite = [
			'slug'       => 'speakers',
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
			'menu_icon'           => 'dashicons-megaphone',
			'description'         => __( 'An speaker post type', 'haystack-civicrm-utilities' ),
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

		// Set up the post type called "Speaker".
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
				__( 'Speaker updated. <a href="%s">View Speaker</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2  => __( 'Custom field updated.', 'haystack-civicrm-utilities' ),
			3  => __( 'Custom field deleted.', 'haystack-civicrm-utilities' ),
			4  => __( 'Speaker updated.', 'haystack-civicrm-utilities' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5  => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: The date and time of the revision. */
					__( 'Speaker restored to revision from %s', 'haystack-civicrm-utilities' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Speaker published. <a href="%s">View Speaker</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7  => __( 'Speaker saved.', 'haystack-civicrm-utilities' ),

			// Item submitted.
			8  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Speaker submitted. <a target="_blank" href="%s">Preview Speaker</a>', 'haystack-civicrm-utilities' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9  => sprintf(
				/* translators: 1: The date, 2: The permalink. */
				__( 'Speaker scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Speaker</a>', 'haystack-civicrm-utilities' ),
				/* translators: Publish box date format - see https://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'haystack-civicrm-utilities' ), strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Speaker draft updated. <a target="_blank" href="%s">Preview Speaker</a>', 'haystack-civicrm-utilities' ),
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
		$title = __( 'Add the name of the Speaker', 'haystack-civicrm-utilities' );

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
			'name'              => _x( 'Speaker Types', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Speaker Type', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Speaker Types', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Speaker Types', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Speaker Type', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Speaker Type:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Speaker Type', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Speaker Type', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Speaker Type', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Speaker Type Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Speaker Types', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Speaker Types found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'speaker-types',
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
			'name'              => _x( 'Speaker Tags', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Speaker Tag', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Speaker Tags', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Speaker Tags', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Speaker Tag', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Speaker Tag:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Speaker Tag', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Speaker Tag', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Speaker Tag', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Speaker Tag Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Speaker Tags', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Speaker Tags found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'speaker-tags',
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
