<?php
/**
 * This is where we put all the functions that that are
 * difficult or impossible to categorize anywhere else.
 *
 **/

/**
 * WordPress 3.0 compatibility function. It allows you to
 * remove theme support for a certain feature.
 *
 * @todo Remove when WordPress 3.0.1 launches
 */
if( !function_exists('remove_theme_support') ) {
function remove_theme_support( $feature ) {
	
	// Blacklist: for internal registrations not used directly by themes.
	if ( in_array( $feature, array( 'custom-background', 'custom-header', 'editor-style', 'widgets' ) ) )
		return false;

	global $_wp_theme_features;

	if ( ! isset( $_wp_theme_features[$feature] ) )
		return false;
	unset( $_wp_theme_features[$feature] );
	return true;
	
}}

/**
 * Wordpress 3.0 compatibility function. It returns an
 * empty array for making quick filters.
 *
 * @todo Remove when WordPress 3.0.1 launches
 */
if ( !function_exists('__return_empty_array') ) {
function __return_empty_array() {
	return array();
}}

/**
 * Wordpress 3.0 compatibility function. It returns
 * false for making quick filters.
 *
 * @todo Remove when WordPress 3.0.1 launches
 */
if ( !function_exists('__return_false') ) {
function __return_false() {
	return false;
}}

/**
 * Wordpress 3.0 compatibility function. It returns
 * true for making quick filters.
 *
 * @todo Remove when WordPress 3.0.1 launches
 */
if ( !function_exists('__return_true') ) {
function __return_true() {
	return true;
}}

/**
 * Wordpress 3.0 compatibility function. It returns
 * 0 (zero) for making quick filters.
 *
 * @todo Remove when WordPress 3.0.1 launches
 */
if ( !function_exists('__return_zero') ) {
function __return_zero() {
	return 0;
}}