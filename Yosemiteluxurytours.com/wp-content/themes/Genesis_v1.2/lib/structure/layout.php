<?php
/**
 * undocumented 
 *
 */

/**
 * This function loads the stylesheet.
 * If a child theme is active, it loads the child theme's stylesheet,
 * otherwise, it loads the Genesis stylesheet.
 *
 */
add_action('genesis_meta', 'genesis_load_stylesheet');
function genesis_load_stylesheet() {
	
	echo "\n".'<!--The Main Stylesheet-->'."\n";
	echo '<link rel="stylesheet" href="'.get_bloginfo('stylesheet_url').'" type="text/css" media="screen" />'."\n";
	
}

/**
 * This function/filter adds new classes to the <body>
 * so that we can use psuedo-variables in our CSS file,
 * which helps us achieve multiple header layouts with minimal code
 *
 * @since 0.2.2
 */
add_filter('body_class', 'genesis_header_body_classes');
function genesis_header_body_classes($classes) {
	
	// add header classes to $classes array
	if( !genesis_get_option('header_right') )
		$classes[] = 'header-full-width';
		
	if( genesis_get_option('blog_title') == 'image' )
		$classes[] = 'header-image';
	
	// return filtered $classes
	return $classes;
	
}

/**
 * This function checks both the custom field and
 * the theme option to find the user-selected site
 * layout, and returns it.
 *
 * @since 0.2.2
 */
function genesis_site_layout() {
	
	// if is_singular, check for custom field,
	// fallback on theme option if no custom field not set
	if(is_singular()) {
		$custom_field = genesis_get_custom_field( '_genesis_layout' );
		$theme_option = genesis_get_option( 'site_layout' );
		$site_layout = ( $custom_field ) ? $custom_field : $theme_option;
	}
	// else pull the theme option
	else {
		$site_layout = genesis_get_option( 'site_layout' );
	}
	
	return esc_attr( $site_layout );
	
}

/**
 * This function/filter adds new classes to the <body>
 * so that we can use psuedo-variables in our CSS file,
 * which helps us achieve multiple site layouts with minimal code
 *
 * @since 0.2.2
 */
add_filter('body_class', 'genesis_layout_body_classes');
function genesis_layout_body_classes($classes) {
	
	// get the layout
	$site_layout = genesis_site_layout();
	
	// add new class to $classes array
	if($site_layout) $classes[] = $site_layout;
	
	// return filtered $classes
	return $classes;
}

/**
 * This function outputs the sidebar.php file
 * if specified in theme options or in-post options
 *
 * @since 0.2
 */
add_action('genesis_after_content', 'genesis_get_sidebar');
function genesis_get_sidebar() {
	
	// get the layout
	$site_layout = genesis_site_layout();

	// don't load sidebar on pages that don't need it
	if( $site_layout == 'full-width-content' ) return;
	
	// output the primary sidebar
	get_sidebar();
}

/**
 * This function outputs the sidebar_alt.php file
 * if specified in theme options or in-post options
 *
 * @since 0.2
 */
add_action('genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
function genesis_get_sidebar_alt() {
	
	// get the layout
	$site_layout = genesis_site_layout();
	
	// don't load sidebar-alt on pages that don't need it
	if( $site_layout == 'content-sidebar' || 
		$site_layout == 'sidebar-content' || 
		$site_layout == 'full-width-content' ) return;
	
	// output the alternate sidebar
	get_sidebar('alt');

}