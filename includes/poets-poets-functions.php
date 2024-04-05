<?php
/**
 * Football Poets "Poets" Theme functions.
 *
 * Global scope functions that are available to the theme can be found here.
 *
 * @package Poets_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Displays all a Poet's Links.
 *
 * At present, this just means Twitter and Website.
 *
 * @since 0.1.1
 */
function poets_poets_links() {

	// Get twitter.
	$twitter = poets_poets_get_twitter();

	// Get website.
	$website = poets_poets_get_website();

	// If we have either.
	if ( ! empty( $twitter ) || ! empty( $website ) ) {

		// Join with line break.
		$output = implode( '<br>', [ $twitter, $website ] );

		// Show it.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<p>' . $output . '</p>';

	}

}

/**
 * Displays a Poet's Twitter Account.
 *
 * @since 0.1
 */
function poets_poets_twitter() {

	// Show via function below.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo poets_poets_get_twitter();

}

/**
 * Gets a Poet's Twitter Account.
 *
 * @since 0.1.1
 *
 * @return str The Twitter URL, or empty if not set.
 */
function poets_poets_get_twitter() {

	// Access globals.
	global $post;

	// Bail if Post isn't valid.
	if ( ! ( $post instanceof WP_Post ) ) {
		return '';
	}

	// Access plugin.
	$poets_poets = poets_poets();

	// Get meta data key.
	$db_key = '_' . $poets_poets->metaboxes->twitter_meta_key;

	// Show link if custom field has a value.
	$existing = get_post_meta( $post->ID, $db_key, true );
	if ( false !== $existing && ! empty( $existing ) ) {
		return '<a href="https://twitter.com/' . $existing . '">https://twitter.com/' . $existing . '</a>';
	}

	// --<
	return '';

}

/**
 * Displays a link to a Poet's Website.
 *
 * @since 0.1
 */
function poets_poets_website() {

	// Show via function below.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo poets_poets_get_website();

}

/**
 * Gets a link to a Poet's Website.
 *
 * @since 0.1.1
 *
 * @return str The Poet's Website URL, or empty if not set.
 */
function poets_poets_get_website() {

	// Access globals.
	global $post;

	// Bail if Post isn't valid.
	if ( ! ( $post instanceof WP_Post ) ) {
		return '';
	}

	// Access plugin.
	$poets_poets = poets_poets();

	// Get meta data key.
	$db_key = '_' . $poets_poets->metaboxes->website_meta_key;

	// Show link if custom field has a value.
	$existing = get_post_meta( $post->ID, $db_key, true );
	if ( false !== $existing && ! empty( $existing ) ) {
		return '<a href="' . $existing . '">' . $existing . '</a>';
	}

}
