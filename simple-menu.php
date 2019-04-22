<?php
/**
 * Plugin Name: Genesis Simple Menus
 * Plugin URI: https://github.com/copyblogger/genesis-simple-menus
 * Description: Genesis Simple Menus allows you to select a WordPress menu for secondary navigation on individual posts/pages.
 * Version: 1.0.1
 * Author: StudioPress
 * Author URI: http://www.studiopress.com/
 * License: GNU General Public License v2.0 (or later)
 * License URI: https://www.opensource.org/licenses/gpl-license.php
 *
 * Text Domain: genesis-simple-menus
 * Domain Path: /languages
 *
 * @package genesis-simple-menu
 */

/**
 * Load the plugin file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'genesis-simple-menus.php';
