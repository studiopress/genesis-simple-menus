<?php
/**
 * Genesis Simple Menus Class.
 *
 * @package genesis-simple-menus
 */

/**
 * The main class.
 *
 * @since 0.1.0
 */
final class Genesis_Simple_Menus {

	/**
	 * Minimum Genesis Version.
	 *
	 * @var string $min_genesis_version
	 */
	public $min_genesis_version = '2.4.2';

	/**
	 * Minimum WordPress version.
	 *
	 * @var string $min_wp_version.
	 */
	public $min_wp_version = '4.7.2';

	/**
	 * The plugin textdomain, for translations.
	 *
	 * @var string $plugin_textdomain
	 */
	public $plugin_textdomain = 'genesis-simple-menus';

	/**
	 * Core object.
	 *
	 * @var Genesis_Simple_Menus_Core
	 */
	public $core;

	/**
	 * Entry object.
	 *
	 * @var Genesis_Simple_Menus_Entry
	 */
	public $entry;

	/**
	 * Term object.
	 *
	 * @var Genesis_Simple_Menus_Term
	 */
	public $term;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
	}

	/**
	 * Initialize.
	 *
	 * @since 0.1.0
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
	 * @since 1.0.0
	 */
	public function requirements_notice() {

		if ( ! defined( 'PARENT_THEME_VERSION' ) || ! version_compare( PARENT_THEME_VERSION, $this->min_genesis_version, '>=' ) ) {

			$plugin = get_plugin_data( GENESIS_SIMPLE_MENU_PLUGIN_DIR . 'simple-menu.php' );

			$action = defined( 'PARENT_THEME_VERSION' ) ? __( 'upgrade to', 'genesis-simple-menus' ) : __( 'install and activate', 'genesis-simple-menus' );

			// translators: %1$s is the plugin name, %2$s is the minor version, %3$s is the link, %4$s is the Genesis version and %5$s is the action.
			$message = sprintf( __( '%1$s requires WordPress %2$s and <a href="%3$s" target="_blank">Genesis %4$s</a>, or greater. Please %5$s the latest version of Genesis to use this plugin.', 'genesis-simple-menus' ), $plugin['Name'], $this->min_wp_version, 'http://my.studiopress.com/?download_id=91046d629e74d525b3f2978e404e7ffa', $this->min_genesis_version, $action );
			echo '<div class="notice notice-warning"><p>' . wp_kses_post( $message ) . '</p></div>';

		}
	}

	/**
	 * Load the plugin textdomain, for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . 'languages/' );
	}

	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 1.0.0
	 */
	public function instantiate() {

		// Do nothing if secondary menu isn't supported.
		if ( ! genesis_nav_menu_supported( 'secondary' ) ) {
			return;
		}

		require_once GENESIS_SIMPLE_MENU_PLUGIN_DIR . '/includes/class-genesis-simple-menus-core.php';
		$this->core = new Genesis_Simple_Menus_Core();
		$this->core->init();

		require_once GENESIS_SIMPLE_MENU_PLUGIN_DIR . '/includes/class-genesis-simple-menus-entry.php';
		$this->entry = new Genesis_Simple_Menus_Entry();
		$this->entry->init();

		require_once GENESIS_SIMPLE_MENU_PLUGIN_DIR . '/includes/class-genesis-simple-menus-term.php';
		$this->term = new Genesis_Simple_Menus_Term();
		$this->term->init();
	}
}
