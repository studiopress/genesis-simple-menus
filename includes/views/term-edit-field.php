<?php
$menus = get_theme_support( 'genesis-menus' );
$secondary_label = $menus[0]['secondary'];
?>
<h3><?php _e( 'Navigation', 'genesis-simple-menus' ); ?></h3>

<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="genesis-meta[<?php echo $this->meta_key; ?>]"><span><?php echo esc_html( $secondary_label ); ?><span></label>
		</th>
		<td>
			<select name="genesis-meta[<?php echo $this->meta_key; ?>]" id="genesis-meta[<?php echo $this->meta_key; ?>]">
				<option value=""><?php _e( 'Default', 'genesis-simple-menus' ); ?></option>
				<?php
				$menus = wp_get_nav_menus( array( 'orderby' => 'name') );
				foreach ( $menus as $menu ) {
					printf( '<option value="%d" %s>%s</option>', $menu->term_id, selected( $menu->term_id, get_term_meta( $term->term_id, $this->meta_key, true ), false ), esc_html( $menu->name ) );
				}
				?>
			</select>
		</td>
	</tr>
</table>
