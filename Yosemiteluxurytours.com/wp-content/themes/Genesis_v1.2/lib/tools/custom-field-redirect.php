<?php
/**
 * This code adapted from the Custom Field Redirect
 * plugin by Nathan Rice, http://www.nathanrice.net/plugins
 *
 */

if(!function_exists('custom_field_redirect')) {

//Hook the link rewrite function into the page_link filter
//This part replaces the page URL, wherever it is outputted, with our custom URL
add_filter('page_link', 'custom_field_page_link', 10, 2);
function custom_field_page_link($link, $id) {
	
	if( get_post_meta($id, 'mask', true) )
		return $link;
	
	$custom_page_url = get_post_meta($id, 'redirect', true);
	
	if( !empty($custom_page_url) ) $link = esc_url( $custom_page_url );
		return $link;

}

//Hook the redirect function into the template_redirect action
//This part actually does the redirect, if necessary

add_action('template_redirect', 'custom_field_redirect');
function custom_field_redirect() {
	//globalize vars
	global $wp_query;
	
	$redirect = get_post_meta($wp_query->post->ID, 'redirect', true);
	
	if( !empty($redirect) && is_singular() ) {
		//And do the redirect.
		wp_redirect($redirect, 301);
		exit();
	}
}

}
?>