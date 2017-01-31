<?php

/**
 * Add Title/Description to Category/Tag/Taxonomy archive pages.
 */
add_action('genesis_before_loop', 'genesis_do_taxonomy_title_description', 15);
function genesis_do_taxonomy_title_description() {
	global $wp_query;
	
	if ( !is_category() && !is_tag() && !is_tax() )
		return;
		
	if ( get_query_var('paged') >= 2 )
		return;
		
	$term = $wp_query->get_queried_object();
	
	if ( !$term )
		return;

	global $_genesis_formatting_allowedtags;
		
	$title = $term->meta['display_title'] ? sprintf( '<h1>%s</h1>', esc_html( $term->name ) ) : false;
	$description = $term->meta['display_description'] ? wpautop( wp_kses( $term->description, $_genesis_formatting_allowedtags ) ) : false;

	if ( $title || $description ) {
		printf( '<div class="taxonomy-description">%s</div>', $title . $description );
	}
	
}