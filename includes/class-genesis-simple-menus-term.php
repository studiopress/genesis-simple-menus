<?php
/**
 * Term Settings.
 *
 * @package genesis-simple-menus
 */

/**
 * Genesis Simple Menus Term Class.
 */
class Genesis_Simple_Menus_Term {

	/**
	 * The Menu to use.
	 *
	 * @var int
	 */
	public $menu = null;

	/**
	 * The meta key for the user specified menu.
	 *
	 * @var string
	 */
	public $meta_key = '_gsm_menu';

	/**
	 * The supported taxonomies.
	 *
	 * @var array
	 */
	public $taxonomies;

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Add fields to the term edit form.
		add_action( 'admin_init', array( $this, 'add_edit_form' ) );

	}

	/**
	 * Add fields to the term edit form. Added to public taxonomies only, by default.
	 *
	 * @since 1.0.0
	 */
	public function add_edit_form() {

		$_taxonomies = get_taxonomies(
			array(
				'show_ui' => true,
				'public'  => true,
			)
		);

		/**
		 * The supported taxonomies.
		 *
		 * An array of supported taxonomies. All public taxonomies, by default.
		 *
		 * @since 0.1.0
		 *
		 * @param array $taxonomies The supported taxonomies.
		 */
		$this->taxonomies = apply_filters( 'genesis_simple_menus_taxonomies', array_keys( $_taxonomies ) );

		if ( ! is_array( $this->taxonomies ) ) {
			return;
		}

		foreach ( $this->taxonomies as $tax ) {
			add_action( "{$tax}_edit_form", array( $this, 'term_edit_form' ), 9, 2 );
		}

	}

	/**
	 * The edit form fields markup.
	 *
	 * @param array $term Term.
	 * @param array $taxonomy Taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function term_edit_form( $term, $taxonomy ) {

		require_once GENESIS_SIMPLE_MENU_PLUGIN_DIR . '/includes/views/term-edit-field.php';

	}

}
