<?php
/**
 * This file essentially checks for the existence of 3rd party
 * SEO plugins, and disables the Genesis SEO features if they
 * are present.
 *
 * @package Genesis
 * @author Nathan Rice
 **/

add_action('init', 'genesis_seo_compatibility_check', 15);
function genesis_seo_compatibility_check() {
	
	//	Disable all SEO functions if a popular SEO plugin is active
	if ( class_exists('All_in_One_SEO_Pack') || class_exists('HeadSpace_Plugin') || class_exists('Platinum_SEO_Pack') ) {
		remove_filter('wp_title', 'genesis_default_title', 10, 3);
		remove_action('genesis_meta','genesis_seo_meta_description');
		remove_action('genesis_meta','genesis_seo_meta_keywords');
		remove_action('genesis_meta','genesis_index_follow_logic');
		remove_action('genesis_meta','genesis_canonical');
		remove_action('admin_menu', 'genesis_add_inpost_seo_box');
		remove_action('save_post', 'genesis_inpost_seo_save', 1, 2);
		remove_action('category_edit_form', 'genesis_taxonomy_seo_options', 10, 2);
		remove_action('post_tag_edit_form', 'genesis_taxonomy_seo_options', 10, 2);
		remove_filter('get_term', 'genesis_term_meta', 10, 2);
		
		remove_theme_support('genesis-seo-settings-menu');
		add_filter('pre_option_' . GENESIS_SEO_SETTINGS_FIELD, '__return_empty_array');
	}

/*	Maybe in the future
	//	Disable certain Genesis features if Robots Meta plugin is active
	if ( class_exists('RobotsMeta_Admin') ) {
		remove_action('genesis_meta','genesis_seo_meta_description');
		remove_action('genesis_meta', 'genesis_seo_meta_keywords');
		remove_action('genesis_meta','genesis_index_follow_logic');
	}
/**/
	
	//	disable Genesis <title> generation if SEO Title Tag is active
	if (function_exists('seo_title_tag')) {
		remove_filter('wp_title', 'genesis_default_title', 10, 3);
		remove_action('genesis_title', 'wp_title');
		add_action('genesis_title', 'seo_title_tag');
	}
	
}