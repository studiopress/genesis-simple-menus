<?php
/**
 * Term edit fild.
 *
 * @package genesis-simple-menus
 */

$menus           = get_theme_support( 'genesis-menus' );
$secondary_label = $menus[0]['secondary'];
?>
<h3><?php esc_html_e( 'Navigation', 'genesis-simple-menus' ); ?></h3>

<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="genesis-meta[<?php echo esc_attr( $this->meta_key ); ?>]"><span><?php echo esc_html( $secondary_label ); ?><span></label>
		</th>
		<td>
			<select name="genesis-meta[<?php echo esc_attr( $this->meta_key ); ?>]" id="genesis-meta[<?php echo esc_attr( $this->meta_key ); ?>]">
				<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
				<?php
				$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );
				foreach ( $menus as $menu_entry ) {
					printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, get_term_meta( $term->term_id, $this->meta_key, true ), false ), esc_html( $menu_entry->name ) );
				}
				?>
			</select>
		</td>
	</tr>
</table>
