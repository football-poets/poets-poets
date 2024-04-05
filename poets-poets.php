<?php
/**
 * Football Poets Poets
 *
 * Plugin Name: Football Poets Poets
 * Description: Creates a Custom Post Type for the Football Poets site.
 * Plugin URI:  https://github.com/football-poets/poets-poets
 * Version:     0.3.0
 * Author:      Christian Wach
 * Author URI:  https://haystack.co.uk
 * Text Domain: poets-poets
 * Domain Path: /languages
 *
 * @package Poets_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'POETS_POETS_VERSION', '0.3.0' );

// Store reference to this file.
if ( ! defined( 'POETS_POETS_FILE' ) ) {
	define( 'POETS_POETS_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'POETS_POETS_URL' ) ) {
	define( 'POETS_POETS_URL', plugin_dir_url( POETS_POETS_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'POETS_POETS_PATH' ) ) {
	define( 'POETS_POETS_PATH', plugin_dir_path( POETS_POETS_FILE ) );
}

/**
 * Football Poets "Poets" Plugin Class.
 *
 * A class that encapsulates plugin functionality.
 *
 * @since 0.1
 */
class Poets_Poets {

	/**
	 * Custom Post Type object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Poets_Poets_CPT
	 */
	public $cpt;

	/**
	 * Metaboxes object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Poets_Poets_Metaboxes
	 */
	public $metaboxes;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Bootstrap plugin.
		$this->include_files();
		$this->setup_globals();
		$this->register_hooks();

	}

	/**
	 * Include files.
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// Include plugin files.
		include_once POETS_POETS_PATH . 'includes/poets-poets-cpt.php';
		include_once POETS_POETS_PATH . 'includes/poets-poets-metaboxes.php';
		include_once POETS_POETS_PATH . 'includes/poets-poets-functions.php';

	}

	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// Init objects.
		$this->cpt       = new Poets_Poets_CPT();
		$this->metaboxes = new Poets_Poets_Metaboxes();

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Use translation.
		add_action( 'plugins_loaded', [ $this, 'translation' ] );

		// Hooks that always need to be present.
		$this->cpt->register_hooks();
		$this->metaboxes->register_hooks();

	}

	/**
	 * Load translation if present.
	 *
	 * @since 0.1
	 */
	public function translation() {

		// Allow translations to be added.
		// Phpcs:ignore WordPress.WP.DeprecatedParameters.Load_plugin_textdomainParam2Found
		load_plugin_textdomain(
			'poets-poets', // Unique name.
			false, // Deprecated argument.
			dirname( plugin_basename( POETS_POETS_FILE ) ) . '/languages/'
		);

	}

	/**
	 * Perform plugin activation tasks.
	 *
	 * @since 0.1
	 */
	public function activate() {

		// Pass through.
		$this->cpt->activate();

	}

	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// Pass through.
		$this->cpt->deactivate();

	}

}

/**
 * Plugin reference getter.
 *
 * @since 0.1
 *
 * @return Poets_Poets $poets_poets The plugin object.
 */
function poets_poets() {
	static $poets_poets;
	if ( ! isset( $poets_poets ) ) {
		$poets_poets = new Poets_Poets();
	}
	return $poets_poets;
}

// Bootstrap plugin.
poets_poets();

// Activation.
register_activation_hook( __FILE__, [ poets_poets(), 'activate' ] );

// Deactivation.
register_deactivation_hook( __FILE__, [ poets_poets(), 'deactivate' ] );
