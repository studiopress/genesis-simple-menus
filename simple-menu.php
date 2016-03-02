<?php
/*
Plugin Name: Genesis Simple Menus
Plugin URI: http://www.studiopress.com/plugins/simple-menus
Description: Genesis Simple Menus allows you to select a WordPress menu for secondary navigation on individual posts/pages.
Version: 0.4.0
Author: Ron Rennick
Author URI: http://ronandandrea.com/
Text Domain: genesis-simple-menus
*/
/* Copyright:	(C) 2010 Ron Rennick, All rights reserved.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Sample implementation of adding support for custom taxonomy

add_filter( 'genesis_simple_menus_taxonomies', 'gsm_sample_taxonomy' );
function gsm_sample_taxonomy( $taxonomies ) {
	$taxonomies[] = 'taxonomy-slug';
	return array_unique( $taxonomies );
}
*/
class Genesis_Simple_Menus {

	var $handle = 'gsm-post-metabox';
	var $nonce_key = 'gsm-post-metabox-nonce';
	var $field_name = '_gsm_menu';
	var $menu = null;
	var $taxonomies=null;
/*
 * constructor
 */

	function  __construct() {

		add_action( 'init', array( $this, 'init' ), 99 );

		load_plugin_textdomain( 'genesis-simple-menus', false, 'genesis-simple-menus/languages' );

	}
/*
 * add all our base hooks into WordPress
 */
	function init() {

		if ( ! function_exists( 'genesis_get_option' ) )
			return;

		if ( ! genesis_nav_menu_supported( 'secondary' ) )
			return;

		add_action( 'admin_menu',	array( $this, 'admin_menu' ) );
		add_action( 'save_post',	array( $this, 'save_post' ), 10, 2 );
		add_action( 'wp_head',		array( $this, 'wp_head' ) );

		$_taxonomies = get_taxonomies( array( 'show_ui' => true, 'public' => true ) );
		$this->taxonomies = apply_filters( 'genesis_simple_menus_taxonomies', array_keys( $_taxonomies ) );

		if ( empty( $this->taxonomies ) || ! is_array( $this->taxonomies ) )
			return;

		foreach( $this->taxonomies as $tax )
			add_action( "{$tax}_edit_form", array( $this, 'term_edit' ), 9, 2 );

	}
/*
 * Add the post metaboxes to the supported post types
 */
	function admin_menu() {

		foreach( (array) get_post_types( array( 'public' => true ) ) as $type ) {

			if( $type == 'post' || $type == 'page' || post_type_supports( $type, 'genesis-simple-menus' ) )
				add_meta_box( $this->handle, __( 'Secondary Navigation', 'genesis-simple-menus' ), array( $this, 'metabox' ), $type, 'side', 'low' );

		}
	}
/*
 * Does the metabox on the post edit page
 */
	function metabox() {
	?>
	<p>
		<?php
		$this->print_menu_select( $this->field_name, genesis_get_custom_field( $this->field_name ), 'width: 99%;' );
		?>
	</p>
	<?php
	}
/*
 * Does the metabox on the term edit page
 */
	function term_edit( $tag, $taxonomy ) {

		// Merge Defaults to prevent notices
		$tag->meta = wp_parse_args( $tag->meta, array( $this->field_name => '' ) );
	?>
	<h3><?php _e( 'Secondary Navigation', 'genesis' ); ?></h3>

	<table class="form-table">
		<tr class="form-field">
			<th scope="row" valign="top">
				<?php
				$this->print_menu_select( "genesis-meta[{$this->field_name}]", get_term_meta( $tag->term_id, $this->field_name, true ), '', 'padding-right: 10px;', '</th><td>' ); ?>
			</td>
		</tr>
	</table>
	<?php
	}
/*
 * Support function for the metaboxes, outputs the menu dropdown
 */
	function print_menu_select( $field_name, $selected, $select_style = '', $option_style = '', $after_label = '' ) {

		if ( $select_style )
			$select_style = sprintf(' style="%s"', esc_attr( $select_style ) );

		if ( $option_style )
			$option_style = sprintf(' style="%s"', esc_attr( $option_style ) );

		?>
		<label for="<?php echo $field_name; ?>"><span><?php _e( 'Secondary Navigation', 'genesis-simple-menus' ); ?><span></label>

		<?php echo $after_label; ?>

		<select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>"<?php echo $select_style; ?>>
			<option value=""<?php echo $option_style; ?>><?php _e( 'Genesis Default', 'genesis-simple-menus' ); ?></option>
			<?php
			$menus = wp_get_nav_menus( array( 'orderby' => 'name') );
			foreach ( $menus as $menu )
				printf( '<option value="%d" %s>%s</option>', $menu->term_id, selected( $menu->term_id, $selected, false ), esc_html( $menu->name ) );
			?>
		</select>
		<?php
	}
/*
 * Handles the post save & stores the menu selection in the post meta
 */
	function save_post( $post_id, $post ) {

		//	don't try to save the data under autosave, ajax, or future post.
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;
		if ( $post->post_type == 'revision' ) return;
		if ( isset( $_REQUEST['bulk_edit'] ) ) return;

		$perm = 'edit_' . ( 'page' == $post->post_type ? 'page' : 'post' ) . 's';
		if ( ! current_user_can( $perm, $post_id ) )
			return;

		if ( empty( $_POST[$this->field_name] ) )
			delete_post_meta( $post_id, $this->field_name );
		else
			update_post_meta( $post_id, $this->field_name, $_POST[$this->field_name] );

	}
/*
 * Once we hit wp_head, the WordPress query has been run, so we can determine if this request uses a custom subnav
 */
	function wp_head() {

		add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod' ) );

		if ( is_singular() ) {

			$obj = get_queried_object();
			$this->menu = get_post_meta( $obj->ID, $this->field_name, true );
			return;

		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term       = get_queried_object();
			$this->menu = get_term_meta( $term->term_id, $this->field_name, true );
		}

	}
/*
 * Replace the menu selected in the WordPress Menu settings with the custom one for this request
 */
	function theme_mod( $mods ) {

		if ( $this->menu )
			$mods['secondary'] = (int)$this->menu;

		return $mods;

	}
}
/*
 *  giddyup
 */
$gsm_simple_menu = new Genesis_Simple_Menus();
