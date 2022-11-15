<?php
/**
 * "Poets" Custom Post Type class.
 *
 * Handles all Metaboxes for this CPT.
 *
 * @package Poets_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Football Poets "Poets" Custom Post Type Class.
 *
 * A class that encapsulates a Custom Post Type.
 *
 * @since 0.1
 */
class Poets_Poets_CPT {

	/**
	 * Custom Post Type name.
	 *
	 * @since 0.2
	 * @access public
	 * @var object $cpt The name of the Custom Post Type.
	 */
	public $post_type_name = 'poet';

	/**
	 * Taxonomy name.
	 *
	 * @since 0.2
	 * @access public
	 * @var str $taxonomy_name The name of the Custom Taxonomy.
	 */
	public $taxonomy_name = 'poetcat';

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Always create post types.
		add_action( 'init', [ $this, 'post_type_create' ] );

		// Make sure our feedback is appropriate.
		add_filter( 'post_updated_messages', [ $this, 'post_type_messages' ] );

		// Create taxonomy.
		add_action( 'init', [ $this, 'taxonomy_create' ] );

		// Fix hierarchical taxonomy metabox display.
		add_filter( 'wp_terms_checklist_args', [ $this, 'taxonomy_fix_metabox' ], 10, 2 );

		// Add a filter to the wp-admin listing table.
		add_action( 'restrict_manage_posts', [ $this, 'taxonomy_filter_post_type' ] );

		// Add feature image size.
		add_action( 'after_setup_theme', [ $this, 'feature_image_create' ] );

		// Tweak the query for the archive.
		add_action( 'pre_get_posts', [ $this, 'archive_query' ] );

		// Tweak the query for search.
		add_action( 'pre_get_posts', [ $this, 'search_query' ], 100, 1 );

		// Override template for search results.
		add_filter( 'template_include', [ $this, 'search_template' ] );

		// Add to BuddyPress member search.
		add_filter( 'bp_search_form_type_select_options', [ $this, 'search_form_options' ], 10, 1 );

		// Intercept BuddyPress search.
		add_action( 'bp_init', [ $this, 'search_redirect' ], 6 );

	}

	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 0.1
	 */
	public function activate() {

		// Pass through.
		$this->post_type_create();
		$this->taxonomy_create();

		// Go ahead and flush.
		flush_rewrite_rules();

	}

	/**
	 * Actions to perform on plugin deactivation (NOT deletion).
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// Flush rules to reset.
		flush_rewrite_rules();

	}

	// -------------------------------------------------------------------------

	/**
	 * Create our Custom Post Type.
	 *
	 * @since 0.1
	 */
	public function post_type_create() {

		// Only call this once.
		static $registered;

		// Bail if already done.
		if ( $registered ) {
			return;
		}

		// Set up the post type called "Poet".
		register_post_type( $this->post_type_name, [

			// Labels.
			'labels' => [
				'name'               => __( 'Poets', 'poets-poets' ),
				'singular_name'      => __( 'Poet', 'poets-poets' ),
				'add_new'            => __( 'Add New', 'poets-poets' ),
				'add_new_item'       => __( 'Add New Poet', 'poets-poets' ),
				'edit_item'          => __( 'Edit Poet', 'poets-poets' ),
				'new_item'           => __( 'New Poet', 'poets-poets' ),
				'all_items'          => __( 'All Poets', 'poets-poets' ),
				'view_item'          => __( 'View Poet', 'poets-poets' ),
				'search_items'       => __( 'Search Poets', 'poets-poets' ),
				'not_found'          => __( 'No matching Poet found', 'poets-poets' ),
				'not_found_in_trash' => __( 'No Poets found in Trash', 'poets-poets' ),
				'menu_name'          => __( 'Poets', 'poets-poets' ),
			],

			// Defaults.
			'menu_icon'   => 'dashicons-admin-users',
			'description' => __( 'A poet post type', 'poets-poets' ),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'has_archive' => true,
			'query_var' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 25,
			'map_meta_cap' => true,

			// Rewrite.
			'rewrite' => [
				'slug' => 'poets',
				'with_front' => false,
			],

			// Supports.
			'supports' => [
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'author',
			],

		] );

		/*
		// Maybe flush.
		flush_rewrite_rules();
		*/

		// Flag done.
		$registered = true;

	}

	/**
	 * Override messages for a custom post type.
	 *
	 * @since 0.1
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// Access relevant globals.
		global $post, $post_ID;

		// Define custom messages for our custom post type.
		$messages[ $this->post_type_name ] = [

			// Unused - messages start at index 1.
			0 => '',

			// Item updated.
			1 => sprintf(
				/* translators: %s: The URL of the Post. */
				__( 'Poet updated. <a href="%s">View poet</a>', 'poets-poets' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Custom fields.
			2 => __( 'Custom field updated.', 'poets-poets' ),
			3 => __( 'Custom field deleted.', 'poets-poets' ),
			4 => __( 'Poet updated.', 'poets-poets' ),

			// Item restored to a revision.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			5 => isset( $_GET['revision'] ) ?

				// Revision text.
				sprintf(
					// Translators: %s: date and time of the revision.
					__( 'Poet restored to revision from %s', 'poets-poets' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_post_revision_title( (int) $_GET['revision'], false )
				) :

				// No revision.
				false,

			// Item published.
			6 => sprintf(
				/* translators: %s: The URL of the Poem. */
				__( 'Poet published. <a href="%s">View poet</a>', 'poets-poets' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Item saved.
			7 => __( 'Poet saved.', 'poets-poets' ),

			// Item submitted.
			8 => sprintf(
				/* translators: %s: The preview URL. */
				__( 'Poet submitted. <a target="_blank" href="%s">Preview poet</a>', 'poets-poets' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// Item scheduled.
			9 => sprintf(
				/* translators: 1: The Post date, 2: The permalink. */
				__( 'Poet scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview poet</a>', 'poets-poets' ),
				/* translators: Publish box date format, see https://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'poets-poets' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// Draft updated.
			10 => sprintf(
				/* translators: %s: The preview URL. */
				__( 'Poet draft updated. <a target="_blank" href="%s">Preview poet</a>', 'poets-poets' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

		];

		// --<
		return $messages;

	}

	/**
	 * Create our Custom Taxonomy.
	 *
	 * @since 0.1
	 */
	public function taxonomy_create() {

		// Only call this once.
		static $registered;

		// Bail if already done.
		if ( $registered ) {
			return;
		}

		// Define arguments.
		$arguments = [

			// Same as "category".
			'hierarchical' => true,

			// Labels.
			'labels' => [
				'name'              => _x( 'Poet Types', 'taxonomy general name', 'poets-poets' ),
				'singular_name'     => _x( 'Poet Type', 'taxonomy singular name', 'poets-poets' ),
				'search_items'      => __( 'Search Poet Types', 'poets-poets' ),
				'all_items'         => __( 'All Poet Types', 'poets-poets' ),
				'parent_item'       => __( 'Parent Poet Type', 'poets-poets' ),
				'parent_item_colon' => __( 'Parent Poet Type:', 'poets-poets' ),
				'edit_item'         => __( 'Edit Poet Type', 'poets-poets' ),
				'update_item'       => __( 'Update Poet Type', 'poets-poets' ),
				'add_new_item'      => __( 'Add New Poet Type', 'poets-poets' ),
				'new_item_name'     => __( 'New Poet Type Name', 'poets-poets' ),
				'menu_name'         => __( 'Poet Types', 'poets-poets' ),
			],

			// Rewrite rules.
			'rewrite' => [
				'slug' => 'poet-types',
			],

			// Show column in wp-admin.
			'show_admin_column' => true,
			'show_ui' => true,

		];

		// Register a taxonomy for this CPT.
		register_taxonomy(
			$this->taxonomy_name, // Taxonomy name.
			$this->post_type_name, // Post type.
			$arguments
		);

		/*
		// Maybe flush.
		flush_rewrite_rules();
		*/

		// Flag.
		$registered = true;

	}

	/**
	 * Fix the Custom Taxonomy metabox.
	 *
	 * @see https://core.trac.wordpress.org/ticket/10982
	 *
	 * @since 0.1
	 *
	 * @param array $args The existing arguments.
	 * @param int $post_id The WordPress Post ID.
	 */
	public function taxonomy_fix_metabox( $args, $post_id ) {

		// If rendering metabox for our taxonomy.
		if ( isset( $args['taxonomy'] ) && $args['taxonomy'] == $this->taxonomy_name ) {

			// Setting 'checked_ontop' to false seems to fix this.
			$args['checked_ontop'] = false;

		}

		// --<
		return $args;

	}

	/**
	 * Add a filter for this Custom Taxonomy to the Custom Post Type listing.
	 *
	 * @since 0.2
	 */
	public function taxonomy_filter_post_type() {

		// Access current post type.
		global $typenow;

		// Bail if not our post type.
		if ( $typenow != $this->post_type_name ) {
			return;
		}

		// Get tax object.
		$taxonomy = get_taxonomy( $this->taxonomy_name );

		// Show a dropdown.
		wp_dropdown_categories( [
			/* translators: %s: The name of the taxonomy. */
			'show_option_all' => sprintf( __( 'Show All %s', 'poets-poets' ), $taxonomy->label ),
			'taxonomy' => $this->taxonomy_name,
			'name' => $this->taxonomy_name,
			'orderby' => 'name',
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'selected' => isset( $_GET[ $this->taxonomy_name ] ) ? sanitize_text_field( wp_unslash( $_GET[ $this->taxonomy_name ] ) ) : '',
			'show_count' => true,
			'hide_empty' => true,
			'value_field' => 'slug',
			'hierarchical' => 1,
		] );

	}

	/**
	 * Create our Feature Image size.
	 *
	 * @since 0.1
	 */
	public function feature_image_create() {

		// Define a small, square custom image size, cropped to fit.
		add_image_size(
			'poets-poet',
			apply_filters( 'poets_poets_image_width', 384 ),
			apply_filters( 'poets_poets_image_height', 384 ),
			true // Crop.
		);

	}

	/**
	 * Manipulate the "Poets" archive query.
	 *
	 * @since 0.1
	 *
	 * @param object $query The current query passed by reference.
	 */
	public function archive_query( $query ) {

		// Bail for the usual conditions.
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// Handle the target archive.
		if ( ! is_post_type_archive( [ $this->post_type_name ] ) ) {
			return;
		}

		// Let's have a decent number per page.
		$query->set( 'posts_per_page', 50 );

		// Alphabetical.
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );

	}

	/**
	 * Manipulate the "Poets" search results.
	 *
	 * @since 1.0
	 *
	 * @param object $query The current query passed by reference.
	 */
	public function search_query( $query ) {

		// Bail for the usual conditions.
		if ( is_admin() || ! $query->is_search ) {
			return;
		}

		// Make sure only poets are queried.
		if ( ! isset( $query->query['post_type'] ) ) {
			return;
		}
		if ( $query->query['post_type'] != $this->post_type_name ) {
			return;
		}

		// Set post type.
		$query->set( 'post_type', $this->post_type_name );

		/*
		// Logging.
		$e = new \Exception();
		$trace = $e->getTraceAsString();
		error_log( print_r( [
			'method' => __METHOD__,
			'query' => $query,
			//'backtrace' => $trace,
		], true ) );
		*/

	}

	/**
	 * Return searchs back to the "Poets" archive.
	 *
	 * @since 1.0
	 *
	 * @param str $template The template.
	 * @return str $template The modified template.
	 */
	public function search_template( $template ) {

		// Access query.
		global $wp_query;

		// Bail if not search.
		if ( ! $wp_query->is_search ) {
			return $template;
		}

		// Bail if not our post type.
		if ( ! isset( $wp_query->query['post_type'] ) ) {
			return $template;
		}
		if ( $wp_query->query['post_type'] != $this->post_type_name ) {
			return $template;
		}

		// Okay, override template.
		return locate_template( 'archive-' . $this->post_type_name . '.php' );

	}

	/**
	 * Filters the options available in the search dropdown.
	 *
	 * @since 0.2
	 *
	 * @param array $options Existing array of options to add to select field.
	 * @return array $options Modified array of options to add to select field.
	 */
	public function search_form_options( $options ) {

		// Define option text.
		$text = __( 'Poets', 'poets-poets' );

		// Add Poems.
		if ( ! is_array( $options ) ) {
			$options = [ $this->post_type_name => $text ];
		} else {
			$options[ $this->post_type_name ] = $text;
		}

		// --<
		return $options;

	}

	/**
	 * Intercept BuddyPress search queries and redirect.
	 *
	 * @since 0.2
	 */
	public function search_redirect() {

		if ( ! bp_is_current_component( bp_get_search_slug() ) ) {
			return;
		}

		if ( empty( $_POST['search-terms'] ) ) {
			bp_core_redirect( bp_get_root_domain() );
			return;
		}

		// Get form values.
		$search_terms = sanitize_text_field( wp_unslash( $_POST['search-terms'] ) );
		$search_which = ! empty( $_POST['search-which'] ) ? sanitize_text_field( wp_unslash( $_POST['search-which'] ) ) : '';

		// Is it ours?
		if ( $search_which != $this->post_type_name ) {
			return;
		}

		// We haven't registered the CPT yet, so this is hard-coded.
		$page = trailingslashit( home_url( '/poets' ) );

		// Pass terms through.
		$query_string = '?s=' . urlencode( $search_terms ) . '&post_type=' . $this->post_type_name;

		// Redirect to archive.
		bp_core_redirect( $page . $query_string );

	}

}
