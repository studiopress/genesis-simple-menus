<?php
/**
 * Entry settings.
 *
 * @since 1.0.0
 *
 * @package genesis-simple-menus
 */

/**
 * Genesis Simple Menus Entry Class.
 */
class Genesis_Simple_Menus_Entry {

	/**
	 * The metabox handle.
	 *
	 * @var string
	 */
	public $handle = 'gsm-post-metabox';

	/**
	 * The meta key for the user specified menu.
	 *
	 * @var string
	 */
	public $meta_key = '_gsm_menu';

	/**
	 * The nonce action for saving entry meta.
	 *
	 * @var string
	 */
	public $nonce_action = 'gsm-post-metabox-save';

	/**
	 * The nonce key for saving entry meta.
	 *
	 * @var string
	 */
	public $nonce_key = 'gsm-post-metabox-nonce';

	/**
	 * Initialize.
	 */
	public function init() {

		add_action( 'admin_menu', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );

	}

	/**
	 * Add metabox(es) to entry edit screens.
	 */
	public function add_metabox() {

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {

			if ( 'post' === $type || 'page' === $type || post_type_supports( $type, 'genesis-simple-menus' ) ) {
				add_meta_box( $this->handle, __( 'Navigation', 'genesis-simple-menus' ), array( $this, 'metabox' ), $type, 'side', 'low' );
			}
		}

	}

	/**
	 * The metabox content.
	 *
	 * @since 1.0.0
	 */
	public function metabox() {

		require_once GENESIS_SIMPLE_MENU_PLUGIN_DIR . '/includes/views/entry-metabox-content.php';

	}

	/**
	 * Save entry meta on save post.
	 *
	 * @param string $post_id Post Id.
	 * @param Array  $post Post.
	 *
	 * @since 1.0.0
	 */
	public function save_post( $post_id, $post ) {

		// phpcs:ignore
		if ( ! isset( $_POST['genesis_simple_menus'] ) ) {
			return;
		}

		// Merge user submitted options with fallback defaults.
		$data = wp_parse_args(
			// phpcs:ignore
			$_POST['genesis_simple_menus'],
			array(
				'_gsm_menu' => '',
			)
		);

		genesis_save_custom_fields( $data, $this->nonce_action, $this->nonce_key, $post );

	}

}
