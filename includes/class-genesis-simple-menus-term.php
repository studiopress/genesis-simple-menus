<?php
/**
 * Term Settings.
 */
class Genesis_Simple_Menus_Term {

	/**
	 * The Menu to use.
	 */
	public $menu = null;

	/**
	 * The meta key for the user specified menu.
	 */
	public $meta_key = '_gsm_menu';

	/**
	 * The supported taxonomies.
	 */
	public $taxonomies;

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$_taxonomies = get_taxonomies( array( 'show_ui' => true, 'public' => true ) );

		/**
		 * The supported taxonomies.
		 *
		 * An array of supported taxonomies. All public taxonomies by, by default.
		 *
		 * @since 0.1.0
		 *
		 * @param array $taxonomies The supported taxonomies.
		 */
		$this->taxonomies = apply_filters( 'genesis_simple_menus_taxonomies', array_keys( $_taxonomies ) );

		if ( ! is_array( $this->taxonomies ) ) {
			return;
		}

		foreach( $this->taxonomies as $tax ) {
			add_action( "{$tax}_edit_form", array( $this, 'term_edit_form' ), 9, 2 );
		}

	}

	public function term_edit_form( $term, $taxonomy ) {

		require_once( Genesis_Simple_Menus()->plugin_dir_path . 'includes/views/term-edit-field.php' );

	}

}
