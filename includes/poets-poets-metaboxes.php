<?php
/**
 * "Poets" Metaboxes class.
 *
 * Handles all Metaboxes for this CPT.
 *
 * @package Poets_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Football Poets "Poets" Metaboxes Class.
 *
 * A class that encapsulates all Metaboxes for this CPT.
 *
 * @since 0.1
 */
class Poets_Poets_Metaboxes {

	/**
	 * Custom Post Type name.
	 *
	 * @since 0.2
	 * @access public
	 * @var string
	 */
	public $post_type_name = 'poet';

	/**
	 * Email meta key.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $email_meta_key = 'poets_poet_email';

	/**
	 * Twitter Account meta key.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $twitter_meta_key = 'poets_poet_twitter';

	/**
	 * Website meta key.
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	public $website_meta_key = 'poets_poet_website';

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Init when this plugin is loaded.
		add_action( 'poets_poets/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this class.
	 *
	 * @since 0.3.1
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && true === $done ) {
			return;
		}

		// Bootstrap class.
		$this->register_hooks();

		/**
		 * Broadcast that this class is now loaded.
		 *
		 * @since 0.3.1
		 */
		do_action( 'poets_poets/metaboxes/loaded' );

		// We're done.
		$done = true;

	}

	/**
	 * Register hook callbacks.
	 *
	 * @since 0.1
	 */
	private function register_hooks() {

		// Add meta boxes.
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

		// Intercept save.
		add_action( 'save_post', [ $this, 'save_post' ], 1, 2 );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * Adds meta boxes to admin screens
	 *
	 * @since 0.1
	 */
	public function add_meta_boxes() {

		// Add our Email meta box.
		add_meta_box(
			'poets_poets_email',
			__( 'Email Address', 'poets-poets' ),
			[ $this, 'email_box' ],
			$this->post_type_name,
			'advanced'
		);

		// Add our Twitter meta box.
		add_meta_box(
			'poets_poets_twitter',
			__( 'Twitter Account', 'poets-poets' ),
			[ $this, 'twitter_box' ],
			$this->post_type_name,
			'advanced'
		);

		// Add our Website meta box.
		add_meta_box(
			'poets_poets_website',
			__( 'Website', 'poets-poets' ),
			[ $this, 'website_box' ],
			$this->post_type_name,
			'advanced'
		);

	}

	/**
	 * Adds a meta box to CPT edit screens for Email Address Info.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function email_box( $post ) {

		// Use nonce for verification.
		wp_nonce_field( 'poets_poets_emailbox', 'poets_poets_email_nonce' );

		// Set key.
		$db_key = '_' . $this->email_meta_key;

		// Default to empty.
		$val = '';

		// Get value if the custom field already has one.
		$existing = get_post_meta( $post->ID, $db_key, true );
		if ( false !== $existing ) {
			$val = get_post_meta( $post->ID, $db_key, true );
		}

		// Instructions.
		echo '<p>' . esc_html__( 'Please enter the email address for this poet.', 'poets-poets' ) . '</p>';

		// Show a text field.
		echo '<p><input type="text" id="' . esc_attr( $this->email_meta_key ) . '" name="' . esc_attr( $this->email_meta_key ) . '" value="' . esc_attr( $val ) . '" class="regular-text" /></p>';

	}

	/**
	 * Adds a meta box to CPT edit screens for Twitter Account Info.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function twitter_box( $post ) {

		// Use nonce for verification.
		wp_nonce_field( 'poets_poets_twitterbox', 'poets_poets_twitter_nonce' );

		// Set key.
		$db_key = '_' . $this->twitter_meta_key;

		// Default to empty.
		$val = '';

		// Get value if the custom field already has one.
		$existing = get_post_meta( $post->ID, $db_key, true );
		if ( false !== $existing ) {
			$val = get_post_meta( $post->ID, $db_key, true );
		}

		// Instructions.
		echo '<p>' . esc_html__( 'Please enter just the Twitter username. No need to prefix with the @ symbol.', 'poets-poets' ) . '</p>';

		// Show a text field.
		echo '<p><input type="text" id="' . esc_attr( $this->twitter_meta_key ) . '" name="' . esc_attr( $this->twitter_meta_key ) . '" value="' . esc_attr( $val ) . '" class="regular-text" /></p>';

	}

	/**
	 * Adds a meta box to CPT edit screens for Website link.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function website_box( $post ) {

		// Use nonce for verification.
		wp_nonce_field( 'poets_poets_websitebox', 'poets_poets_website_nonce' );

		// Set key.
		$db_key = '_' . $this->website_meta_key;

		// Default to empty.
		$val = '';

		// Get value if the custom field already has one.
		$existing = get_post_meta( $post->ID, $db_key, true );
		if ( false !== $existing ) {
			$val = get_post_meta( $post->ID, $db_key, true );
		}

		// Instructions.
		echo '<p>' . esc_html__( 'Please enter the full website address. For example: https://twitter.com/', 'poets-poets' ) . '</p>';

		// Show a text field.
		echo '<p><input type="text" id="' . esc_attr( $this->website_meta_key ) . '" name="' . esc_attr( $this->website_meta_key ) . '" value="' . esc_attr( $val ) . '" class="regular-text" /></p>';

	}

	/**
	 * Stores our additional params.
	 *
	 * @since 0.1
	 *
	 * @param integer $post_id the ID of the post or revision.
	 * @param integer $post the post object.
	 */
	public function save_post( $post_id, $post ) {

		// We don't use post_id because we're not interested in revisions.

		// Store our Email Address metadata.
		$this->save_email_meta( $post );

		// Store our Twitter Account metadata.
		$this->save_twitter_meta( $post );

		// Store our Website metadata.
		$this->save_website_meta( $post );

	}

	// -----------------------------------------------------------------------------------

	/**
	 * When a post is saved, this also saves the metadata.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post_obj The object for the post or revision.
	 */
	private function save_email_meta( $post_obj ) {

		// If no post, kick out.
		if ( ! $post_obj ) {
			return;
		}

		// Authenticate.
		$nonce = isset( $_POST['poets_poets_email_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['poets_poets_email_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'poets_poets_emailbox' ) ) {
			return;
		}

		// Is this an auto save routine?
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_obj->ID ) ) {
			return;
		}

		// Check for revision.
		if ( 'revision' === $post_obj->post_type ) {

			// Get parent.
			if ( 0 !== (int) $post_obj->post_parent ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}

		} else {
			$post = $post_obj;
		}

		// Bail if not creator post type.
		if ( $this->post_type_name !== $post->post_type ) {
			return;
		}

		// Now process metadata.

		// Define key.
		$db_key = '_' . $this->email_meta_key;

		// Get value.
		$value = isset( $_POST[ $this->email_meta_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->email_meta_key ] ) ) : '';

		// Save for this post.
		$this->save_meta( $post, $db_key, $value );

	}

	/**
	 * When a post is saved, this also saves the metadata.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post_obj The object for the post or revision.
	 */
	private function save_twitter_meta( $post_obj ) {

		// If no post, kick out.
		if ( ! $post_obj ) {
			return;
		}

		// Authenticate.
		$nonce = isset( $_POST['poets_poets_twitter_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['poets_poets_twitter_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'poets_poets_twitterbox' ) ) {
			return;
		}

		// Is this an auto save routine?
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_obj->ID ) ) {
			return;
		}

		// Check for revision.
		if ( 'revision' === $post_obj->post_type ) {

			// Get parent.
			if ( 0 !== (int) $post_obj->post_parent ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}

		} else {
			$post = $post_obj;
		}

		// Bail if not creator post type.
		if ( $post->post_type !== $this->post_type_name ) {
			return;
		}

		// Now process metadata.

		// Define key.
		$db_key = '_' . $this->twitter_meta_key;

		// Get value.
		$value = isset( $_POST[ $this->twitter_meta_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->twitter_meta_key ] ) ) : '';

		// Strip @ symbol if present.
		if ( substr( $value, 0, 1 ) === '@' ) {
			$value = substr( $value, 1 );
		}

		// Strip https://twitter.com/ if present.
		if ( substr( $value, 0, 20 ) === 'https://twitter.com/' ) {
			$value = substr( $value, 20 );
		}

		// Save for this post.
		$this->save_meta( $post, $db_key, $value );

	}

	/**
	 * When a post is saved, this also saves the metadata.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post_obj The object for the post or revision.
	 */
	private function save_website_meta( $post_obj ) {

		// If no post, kick out.
		if ( ! $post_obj ) {
			return;
		}

		// Authenticate.
		$nonce = isset( $_POST['poets_poets_website_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['poets_poets_website_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'poets_poets_websitebox' ) ) {
			return;
		}

		// Is this an auto save routine?
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_obj->ID ) ) {
			return;
		}

		// Check for revision.
		if ( 'revision' === $post_obj->post_type ) {

			// Get parent.
			if ( 0 !== (int) $post_obj->post_parent ) {
				$post = get_post( $post_obj->post_parent );
			} else {
				$post = $post_obj;
			}

		} else {
			$post = $post_obj;
		}

		// Bail if not our post type.
		if ( $post->post_type !== $this->post_type_name ) {
			return;
		}

		// Now process metadata.

		// Define key.
		$db_key = '_' . $this->website_meta_key;

		// Get value.
		$value = isset( $_POST[ $this->website_meta_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->website_meta_key ] ) ) : '';

		// Save for this post.
		$this->save_meta( $post, $db_key, $value );

	}

	/**
	 * Utility to automate metadata saving.
	 *
	 * @since 0.1
	 *
	 * @param WP_Post $post The WordPress post object.
	 * @param string  $key The meta key.
	 * @param mixed   $data The data to be saved.
	 * @return mixed $data The data that was saved.
	 */
	private function save_meta( $post, $key, $data = '' ) {

		// If the custom field already has a value.
		$existing = get_post_meta( $post->ID, $key, true );
		if ( false !== $existing ) {

			// Update the data.
			update_post_meta( $post->ID, $key, $data );

		} else {

			// Add the data.
			add_post_meta( $post->ID, $key, $data, true );

		}

		// --<
		return $data;

	}

}
