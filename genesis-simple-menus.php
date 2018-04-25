<?php
/**
 * Genesis Simple Menus
 *
 * @package StudioPress\GenesisSimpleMenus
 */

/**
 * The main class.
 *
 * @since 0.1.0
 */
final class Genesis_Simple_Menus {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $plugin_version = '1.0.1';

	/**
	 * Minimum Genesis Version.
	 *
	 * @var string
	 */
	public $min_genesis_version = '2.4.2';

	/**
	 * Minimum WordPress version.
	 *
	 * @var string
	 */
	public $min_wp_version = '4.7.2';

	/**
	 * The plugin textdomain, for translations.
	 *
	 * @var string
	 */
	public $plugin_textdomain = 'genesis-simple-menus';

	/**
	 * The url to the plugin directory.
	 *
	 * @var string
	 */
	public $plugin_dir_url;

	/**
	 * The path to the plugin directory.
	 *
	 * @var string
	 */
	public $plugin_dir_path;

	/**
	 * Supported menus.
	 *
	 * @var string
	 */
	public $menus;

	/**
	 * Core object.
	 *
	 * @var object
	 */
	public $core;

	/**
	 * Entry object.
	 *
	 * @var object
	 */
	public $entry;

	/**
	 * Term object.
	 *
	 * @var object
	 */
	public $term;

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );

	}

	/**
	 * Initialize.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {

		$this->load_plugin_textdomain();

		add_action( 'admin_notices', array( $this, 'requirements_notice' ) );

		/**
		 * Include and Instantiate.
		 */
		add_action( 'genesis_setup', array( $this, 'instantiate' ) );

	}


	/**
	 * Show admin notice if minimum requirements aren't met.
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public function requirements_notice() {

		if ( ! defined( 'PARENT_THEME_VERSION' ) || ! version_compare( PARENT_THEME_VERSION, $this->min_genesis_version, '>=' ) ) {

			$plugin = get_plugin_data( $this->plugin_dir_path . 'simple-menu.php' );

			$action = defined( 'PARENT_THEME_VERSION' ) ? __( 'upgrade to', 'genesis-simple-menus' ) : __( 'install and activate', 'genesis-simple-menus' );

			/* translators: 1 plugin name, 2 minimum WP version, 3 StudioPress link, 4 minimum Genesis version, 5 action to take  */
			$message = sprintf( __( '%1$s requires WordPress %2$s and <a href="%3$s" target="_blank">Genesis %4$s</a>, or greater. Please %5$s the latest version of Genesis to use this plugin.', 'genesis-simple-menus' ), $plugin['Name'], $this->min_wp_version, 'http://my.studiopress.com/?download_id=91046d629e74d525b3f2978e404e7ffa', $this->min_genesis_version, $action );
			echo '<div class="notice notice-warning"><p>' . $message . '</p></div>';

		}

	}

	/**
	 * Load the plugin textdomain, for translation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . 'languages/' );
	}

	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function instantiate() {

		// Do nothing if secondary menu isn't supported.
		if ( ! genesis_nav_menu_supported( 'secondary' ) ) {
			return;
		}

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-menus-core.php' );
		$this->core = new Genesis_Simple_Menus_Core();
		$this->core->init();

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-menus-entry.php' );
		$this->entry = new Genesis_Simple_Menus_Entry();
		$this->entry->init();

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-menus-term.php' );
		$this->term = new Genesis_Simple_Menus_Term();
		$this->term->init();

	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 1.0.0
 *
 * @return object
 */
function Genesis_Simple_Menus() {

	static $object;

	if ( null == $object ) {
		$object = new Genesis_Simple_Menus();
	}

	return $object;

}

/*
 * Initialize the object on	`plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Genesis_Simple_Menus(), 'init' ) );
