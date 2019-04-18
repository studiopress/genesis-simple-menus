<?php
/**
 * Entry metabox content.
 *
 * @package genesis-simple-menus
 */

$menus           = get_theme_support( 'genesis-menus' );
$secondary_label = $menus[0]['secondary'];
wp_nonce_field( $this->nonce_action, $this->nonce_key );
?>
<label for="genesis_simple_menus[<?php echo esc_attr( $this->meta_key ); ?>]"><span><?php echo esc_html( $secondary_label ); ?><span></label>

<select name="genesis_simple_menus[<?php echo esc_attr( $this->meta_key ); ?>]" id="genesis_simple_menus[<?php echo esc_attr( $this->meta_key ); ?>]">
	<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
	<?php
	$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
	foreach ( $menus as $menu_entry ) {
		printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, genesis_get_custom_field( $this->meta_key ), false ), esc_html( $menu_entry->name ) );
	}
	?>
</select>
