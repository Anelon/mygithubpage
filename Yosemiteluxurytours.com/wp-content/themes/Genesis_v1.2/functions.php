<?php
/**
 * This file calls the init.php file, but only
 * if the child theme hasn't called it first.
 *
 * This method allows the child theme to load
 * the framework so it can use the framework
 * components immediately.
 *
 * @package Genesis
 *
 **/

require_once(TEMPLATEPATH.'/lib/init.php');
//add_action('wp_head', 'echo_wp_query');
function echo_wp_query() {
	global $wp_query;
	print_r($wp_query);
}