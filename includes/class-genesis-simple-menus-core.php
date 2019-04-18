<?php
/**
 * The Core functionality.
 *
 * @since 1.0.0
 *
 * @package genesis-simple-menus
 */

/**
 * Genesis Simple Menus Core Class.
 */
class Genesis_Simple_Menus_Core {

	/**
	 * The Menu to use.
	 *
	 * @var int
	 */
	public $menu = null;

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		add_action( 'wp_head', array( $this, 'swap_menus' ) );

	}

	/**
	 * Swap the default and user selected menus.
	 *
	 * @since 1.0.0
	 */
	public function swap_menus() {

		add_filter( 'theme_mod_nav_menu_locations', array( $this, 'menu_locations_theme_mod' ) );

		if ( is_singular() ) {

			$obj        = get_queried_object();
			$this->menu = get_post_meta( $obj->ID, Genesis_Simple_Menus()->entry->meta_key, true );
			return;

		}

		if ( is_category() || is_tag() || is_tax() ) {

			$term       = get_queried_object();
			$this->menu = get_term_meta( $term->term_id, Genesis_Simple_Menus()->term->meta_key, true );

		}

	}

	/**
	 * Filter the menu locations.
	 *
	 * @param Array $mods Theme mods.
	 *
	 * @since 1.0.0
	 */
	public function menu_locations_theme_mod( $mods ) {

		if ( $this->menu ) {
			$mods['secondary'] = (int) $this->menu;
		}

		return $mods;

	}

}
