<?php
/**
 * Genesis Simple Menus
 *
 * @package StudioPress\GenesisSimpleMenus
 */

$menus           = get_theme_support( 'genesis-menus' );
$primary_label   = $menus[0]['primary'];
$secondary_label = $menus[0]['secondary'];
?>

<h3><?php esc_html_e( 'Navigation', 'genesis-simple-menus' ); ?></h3>

<table class="form-table">
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="genesis-meta[<?php echo esc_attr( $this->primary_key ); ?>]"><span><?php echo esc_html( $primary_label ); ?><span></label>
		</th>
		<td>
			<select name="genesis-meta[<?php echo esc_attr( $this->primary_key ); ?>]" id="genesis-meta[<?php echo esc_attr( $this->primary_key ); ?>]">
				<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
				<?php
				$menus = wp_get_nav_menus(
					array(
						'orderby' => 'name',
					)
				);
				foreach ( $menus as $menu_entry ) {
					printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, get_term_meta( $term->term_id, $this->primary_key, true ), false ), esc_html( $menu_entry->name ) );
				}
				?>
			</select>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="genesis-meta[<?php echo esc_attr( $this->secondary_key ); ?>]"><span><?php echo esc_html( $secondary_label ); ?><span></label>
		</th>
		<td>
			<select name="genesis-meta[<?php echo esc_attr( $this->secondary_key ); ?>]" id="genesis-meta[<?php echo esc_attr( $this->secondary_key ); ?>]">
				<option value=""><?php esc_html_e( 'Default', 'genesis-simple-menus' ); ?></option>
				<?php
				$menus = wp_get_nav_menus(
					array(
						'orderby' => 'name',
					)
				);
				foreach ( $menus as $menu_entry ) {
					printf( '<option value="%d" %s>%s</option>', esc_attr( $menu_entry->term_id ), selected( $menu_entry->term_id, get_term_meta( $term->term_id, $this->secondary_key, true ), false ), esc_html( $menu_entry->name ) );
				}
				?>
			</select>
		</td>
	</tr>
</table>
