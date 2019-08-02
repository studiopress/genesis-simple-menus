<?php
/**
 * Genesis Simple Menus
 *
 * @package StudioPress\GenesisSimpleMenus
 */

$menus = get_theme_support( 'genesis-menus' );
$primary_label   = $menus[0]['primary'];
$secondary_label = $menus[0]['secondary'];
wp_nonce_field( $this->nonce_action, $this->nonce_key );
?>

<p class="post-attributes-label-wrapper">
<label for="genesis_simple_menus[<?php echo esc_attr( $this->primary_key ); ?>]"><span><?php echo esc_html( $primary_label ); ?></span></label>
</p>

<select name="genesis_simple_menus[<?php echo esc_attr( $this->primary_key ); ?>]" id="genesis_simple_menus[<?php echo esc_attr( $this->primary_key ); ?>]">
	<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
	<?php
	$menus = wp_get_nav_menus( array(
		'orderby' => 'name',
	) );
	foreach ( $menus as $menu_entry ) {
		printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, genesis_get_custom_field( $this->primary_key ), false ), esc_html( $menu_entry->name ) );
	}
	?>
</select>

<br>

<p class="post-attributes-label-wrapper">
<label for="genesis_simple_menus[<?php echo esc_attr( $this->secondary_key ); ?>]"><span><?php echo esc_html( $secondary_label ); ?></span></label>
</p>

<select name="genesis_simple_menus[<?php echo esc_attr( $this->secondary_key ); ?>]" id="genesis_simple_menus[<?php echo esc_attr( $this->secondary_key ); ?>]">
	<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
	<?php
	$menus = wp_get_nav_menus( array(
		'orderby' => 'name',
	) );
	foreach ( $menus as $menu_entry ) {
		printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, genesis_get_custom_field( $this->secondary_key ), false ), esc_html( $menu_entry->name ) );
	}
	?>
</select>
