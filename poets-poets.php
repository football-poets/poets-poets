<?php
/**
 * Football Poets Poets
 *
 * Plugin Name: Football Poets Poets
 * Description: Creates a Custom Post Type for the Football Poets site.
 * Plugin URI:  https://github.com/football-poets/poets-poets
 * Version:     0.3.1
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
define( 'POETS_POETS_VERSION', '0.3.1' );

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

		// Initialise when all plugins are loaded.
		add_action( 'plugins_loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialises this plugin.
	 *
	 * @since 0.3.1
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && true === $done ) {
			return;
		}

		// Bootstrap plugin.
		$this->include_files();
		$this->setup_globals();
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is now loaded.
		 *
		 * @since 0.3.1
		 */
		do_action( 'poets_poets/loaded' );

		// We're done.
		$done = true;

	}

	/**
	 * Include files.
	 *
	 * @since 0.1
	 */
	private function include_files() {

		// Include plugin files.
		include POETS_POETS_PATH . 'includes/poets-poets-cpt.php';
		include POETS_POETS_PATH . 'includes/poets-poets-metaboxes.php';
		include POETS_POETS_PATH . 'includes/poets-poets-functions.php';

	}

	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 */
	private function setup_globals() {

		// Init objects.
		$this->cpt       = new Poets_Poets_CPT();
		$this->metaboxes = new Poets_Poets_Metaboxes();

	}

	/**
	 * Register hook callbacks.
	 *
	 * @since 0.1
	 */
	private function register_hooks() {

		// Use translation.
		add_action( 'init', [ $this, 'translation' ] );

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

		// Maybe initialise.
		$this->initialise();

		// Pass through.
		$this->cpt->activate();

	}

	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 0.1
	 */
	public function deactivate() {

		// Maybe initialise.
		$this->initialise();

		// Pass through.
		$this->cpt->deactivate();

	}

}

/**
 * Plugin reference getter.
 *
 * @since 0.1
 *
 * @return Poets_Poets $plugin The plugin object.
 */
function poets_poets() {

	// Store instance in static variable.
	static $plugin = false;

	// Maybe return instance.
	if ( false === $plugin ) {
		$plugin = new Poets_Poets();
	}

	// --<
	return $plugin;

}

// Bootstrap plugin.
poets_poets();

// Activation.
register_activation_hook( __FILE__, [ poets_poets(), 'activate' ] );

// Deactivation.
register_deactivation_hook( __FILE__, [ poets_poets(), 'deactivate' ] );
