<?php
/**
 * Genesis Simple Menus
 *
 * @package StudioPress\GenesisSimpleMenus
 */

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
	 * Primary menu.
	 *
	 * @var string
	 */
	public $primary = null;

	/**
	 * Secondary menu.
	 *
	 * @var string
	 */
	public $secondary = null;

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

			$obj             = get_queried_object();
			$this->primary   = get_post_meta( $obj->ID, Genesis_Simple_Menus()->entry->primary_key, true );
			$this->secondary = get_post_meta( $obj->ID, Genesis_Simple_Menus()->entry->secondary_key, true );
			return;

		}

		if ( is_category() || is_tag() || is_tax() ) {

			$term            = get_queried_object();
			$this->primary   = get_term_meta( $term->term_id, Genesis_Simple_Menus()->term->primary_key, true );
			$this->secondary = get_term_meta( $term->term_id, Genesis_Simple_Menus()->term->secondary_key, true );

		}
	}

	/**
	 * Filter the menu locations.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $mods Menu locations theme mods.
	 *
	 * @return array
	 */
	public function menu_locations_theme_mod( $mods ) {

		if ( $this->primary || $this->secondary ) {
			if ( ! empty( $this->primary ) ) {
				$mods['primary'] = (int) $this->primary;
			}

			if ( ! empty( $this->secondary ) ) {
				$mods['secondary'] = (int) $this->secondary;
			}
		}

		return $mods;
	}
}
