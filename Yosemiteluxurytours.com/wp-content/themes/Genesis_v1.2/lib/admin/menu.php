<?php
/*
 * @todo Document this file
 */

//	This function adds the top-level menu
add_action('admin_menu', 'genesis_add_admin_menu');
function genesis_add_admin_menu() {
	global $menu;
	
	if( !current_theme_supports('genesis-admin-menu') ) return;
	
	// Create the new separator
	if(version_compare(get_bloginfo("version"), '2.9', '>='))
	$menu['58.995'] = array( '', 'manage_options', 'separator-genesis', '', 'wp-menu-separator' );
	
	// Create the new top-level Menu
	add_menu_page('Genesis', 'Genesis', 'manage_options', 'genesis', 'genesis_theme_settings_admin', PARENT_URL.'/images/favicon.ico', '58.996');
}

// This function adds the submenus
add_action('admin_menu', 'genesis_add_admin_submenus');
function genesis_add_admin_submenus() {
	global	$_genesis_theme_settings_pagehook,
			$_genesis_seo_settings_pagehook;
			
	if( !current_theme_supports('genesis-admin-menu') ) return;
	
	// Add "Theme Options" submenu
	$_genesis_theme_settings_pagehook = add_submenu_page('genesis', __('Theme Settings','genesis'), __('Theme Settings','genesis'), 'manage_options', 'genesis', 'genesis_theme_settings_admin');
	
	// Add "SEO Settings" submenu
	$_genesis_seo_settings_pagehook = current_theme_supports('genesis-seo-settings-menu') ? add_submenu_page('genesis', __('SEO Settings','genesis'), __('SEO Settings','genesis'), 'manage_options', 'seo-settings', 'genesis_seo_settings_admin') : null;
	
	// Add "Purchase Themes" submenu
	$_genesis_purchase_themes_pagehook = current_theme_supports('genesis-purchase-menu') ? add_submenu_page('genesis', __('Purchase Themes','genesis'), __('Purchase Themes','genesis'), 'manage_options', 'purchase-themes', 'genesis_purchase_themes_admin') : null;
	
}