genesis-simple-menus
====================

Genesis Simple Menus

## Sample implementation of adding support for custom taxonomy

```
add_filter( 'genesis_simple_menus_taxonomies', 'gsm_sample_taxonomy' );
function gsm_sample_taxonomy( $taxonomies ) {
	$taxonomies[] = 'taxonomy-slug';
	return array_unique( $taxonomies );
}
```
