<?php
/**
 * Students Custom Post Type class.
 *
 * Handles providing a "Students" Custom Post Type.
 *
 * @package Haystack_CU
 * @since 1.0.0
 */

namespace Haystack_CU\CPT;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Students Custom Post Type class.
 *
 * A class that encapsulates a "Students" Custom Post Type.
 *
 * @since 1.0.0
 */
class Students extends Base {

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
	public $post_type_name = 'student';

	/**
	 * Custom Post Type REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $post_type_rest_base = 'students';

	/**
	 * Taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_name = 'student-type';

	/**
	 * Taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy_rest_base = 'student-type';

	/**
	 * Free taxonomy name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_name = 'student-tag';

	/**
	 * Free taxonomy REST base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $tag_rest_base = 'student-tag';

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
			'name'               => __( 'Students', 'haystack-civicrm-utilities' ),
			'singular_name'      => __( 'Student', 'haystack-civicrm-utilities' ),
			'add_new'            => __( 'Add New', 'haystack-civicrm-utilities' ),
			'add_new_item'       => __( 'Add New Student', 'haystack-civicrm-utilities' ),
			'edit_item'          => __( 'Edit Student', 'haystack-civicrm-utilities' ),
			'new_item'           => __( 'New Student', 'haystack-civicrm-utilities' ),
			'all_items'          => __( 'All Students', 'haystack-civicrm-utilities' ),
			'view_item'          => __( 'View Student', 'haystack-civicrm-utilities' ),
			'search_items'       => __( 'Search Students', 'haystack-civicrm-utilities' ),
			'not_found'          => __( 'No matching Student found', 'haystack-civicrm-utilities' ),
			'not_found_in_trash' => __( 'No Students found in Trash', 'haystack-civicrm-utilities' ),
			'menu_name'          => __( 'Students', 'haystack-civicrm-utilities' ),
		];

		// Rewrite.
		$rewrite = [
			'slug'       => 'students',
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
			'menu_icon'           => 'dashicons-welcome-learn-more',
			'description'         => __( 'A student post type', 'haystack-civicrm-utilities' ),
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

		// Set up the post type called "Student".
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
				__( 'Student updated. <a href="%s">View Student</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2  => __( 'Custom field updated.', 'haystack-civicrm-utilities' ),
			3  => __( 'Custom field deleted.', 'haystack-civicrm-utilities' ),
			4  => __( 'Student updated.', 'haystack-civicrm-utilities' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5  => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					/* translators: %s: The date and time of the revision. */
					__( 'Student restored to revision from %s', 'haystack-civicrm-utilities' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Student published. <a href="%s">View Student</a>', 'haystack-civicrm-utilities' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7  => __( 'Student saved.', 'haystack-civicrm-utilities' ),

			// Item submitted.
			8  => sprintf(
				/* translators: %s: The permalink. */
				__( 'Student submitted. <a target="_blank" href="%s">Preview Student</a>', 'haystack-civicrm-utilities' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9  => sprintf(
				/* translators: 1: The date, 2: The permalink. */
				__( 'Student scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Student</a>', 'haystack-civicrm-utilities' ),
				/* translators: Publish box date format - see https://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'haystack-civicrm-utilities' ), strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: The permalink. */
				__( 'Student draft updated. <a target="_blank" href="%s">Preview Student</a>', 'haystack-civicrm-utilities' ),
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
		$title = __( 'Add the name of the Student', 'haystack-civicrm-utilities' );

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
			'name'              => _x( 'Student Types', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Student Type', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Student Types', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Student Types', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Student Type', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Student Type:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Student Type', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Student Type', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Student Type', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Student Type Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Student Types', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Student Types found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'student-types',
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
			'name'              => _x( 'Student Tags', 'taxonomy general name', 'haystack-civicrm-utilities' ),
			'singular_name'     => _x( 'Student Tag', 'taxonomy singular name', 'haystack-civicrm-utilities' ),
			'search_items'      => __( 'Search Student Tags', 'haystack-civicrm-utilities' ),
			'all_items'         => __( 'All Student Tags', 'haystack-civicrm-utilities' ),
			'parent_item'       => __( 'Parent Student Tag', 'haystack-civicrm-utilities' ),
			'parent_item_colon' => __( 'Parent Student Tag:', 'haystack-civicrm-utilities' ),
			'edit_item'         => __( 'Edit Student Tag', 'haystack-civicrm-utilities' ),
			'update_item'       => __( 'Update Student Tag', 'haystack-civicrm-utilities' ),
			'add_new_item'      => __( 'Add New Student Tag', 'haystack-civicrm-utilities' ),
			'new_item_name'     => __( 'New Student Tag Name', 'haystack-civicrm-utilities' ),
			'menu_name'         => __( 'Student Tags', 'haystack-civicrm-utilities' ),
			'not_found'         => __( 'No Student Tags found', 'haystack-civicrm-utilities' ),
		];

		// Rewrite rules.
		$rewrite = [
			'slug' => 'student-tags',
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

		// Register a free Taxonomy for this CPT.
		register_taxonomy( $this->tag_name, $this->post_type_name, $args );

		// Flag done.
		$registered = true;

	}

}
