<?php
/**
 * Genesis Simple Menus file.
 *
 * @package genesis-simple-menus
 */

/**
 * Load the plugin file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GENESIS_SIMPLE_MENU_SETTINGS_FIELD', 'genesis_simple_menu_settings' );
define( 'GENESIS_SIMPLE_MENU_VERSION', '1.1.2' );
define( 'GENESIS_SIMPLE_MENU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GENESIS_SIMPLE_MENU_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-genesis-simple-menus.php';

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 1.0.0
 *
 * @return object
 */
function genesis_simple_menus() {

	static $object;

	if ( null === $object ) {
		$object = new Genesis_Simple_Menus();
	}

	return $object;
}

/**
 * Initialize the object on `plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Genesis_Simple_Menus(), 'init' ) );
