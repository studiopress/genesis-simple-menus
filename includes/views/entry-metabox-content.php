<?php
$menus = get_theme_support( 'genesis-menus' );
$secondary_label = $menus[0]['secondary'];
wp_nonce_field( $this->nonce_action, $this->nonce_key );
?>
<label for="genesis_simple_menus[<?php echo $this->meta_key; ?>]"><span><?php echo esc_html( $secondary_label ); ?><span></label>

<select name="genesis_simple_menus[<?php echo $this->meta_key; ?>]" id="genesis_simple_menus[<?php echo $this->meta_key; ?>]">
	<option value=""><?php _e( 'Default', 'genesis-simple-menus' ); ?></option>
	<?php
	$menus = wp_get_nav_menus( array( 'orderby' => 'name') );
	foreach ( $menus as $menu ) {
		printf( '<option value="%d" %s>%s</option>', $menu->term_id, selected( $menu->term_id, genesis_get_custom_field( $this->meta_key ), false ), esc_html( $menu->name ) );
	}
	?>
</select>
