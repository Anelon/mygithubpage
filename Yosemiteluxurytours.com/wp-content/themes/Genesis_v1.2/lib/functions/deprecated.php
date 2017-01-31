<?php
/**
 * This file is home to the code that has
 * been deprecated/replaced by other code.
 *
 * It serves as a compatibility mechanism.
 *
 * @package Genesis
 **/

/**
 * @deprecated in 0.1.3
 *
 **/
function get_custom_field($field, $echo = TRUE) {
	_deprecated_function( __FUNCTION__, '0.1.3', 'genesis_custom_field()' );
	
	if($echo)
		genesis_custom_field($field);
	else
		return genesis_get_custom_field($field);
}

/**
 * @deprecated in 0.1.3
 *
 **/
function sp_get_option($key, $setting = null) {
	_deprecated_function( __FUNCTION__, '0.1.3', 'genesis_get_option()' );

	return genesis_get_option($key, $setting);
}
function sp_option($key, $setting = null) {
	_deprecated_function( __FUNCTION__, '0.1.3', 'genesis_option()' );
	
	genesis_option($key, $settings);
}

/**
 * @deprecated in 0.1.6
 *
 */
function sp_get_image_id($num = 0) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'genesis_get_image_id()' );
	
	return genesis_get_image_id($num);
}
function sp_get_image($format = 'html', $size = 'full', $class = null, $num = 0, $w = FALSE, $h = FALSE, $zc = 1) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'get_image()' );
	
	$args = array('format' => $format, 'size' => $size, 'class' => $class, 'num' => $num, 'w' => $w, 'h' => $h, 'zc' => $zc);
	return genesis_get_image($args);
}
function sp_image($format = 'html', $size = 'full', $class= null, $num = 0, $w = FALSE, $h = FALSE, $zc = 1) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'genesis_image()' );
	
	$args = array('format' => $format, 'size' => $size, 'class' => $class, 'num' => $num, 'w' => $w, 'h' => $h, 'zc' => $zc);
	genesis_image($args);
}

/**
 * @deprecated in 0.1.6
 *
 */
function sp_page_checklist($name = '', $selected = array()) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'genesis_page_checklist()' );
	
	genesis_page_checklist($name, $selected);
}
function sp_category_checklist($name = '', $selected = array()) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'genesis_category_checklist()' );
	
	genesis_category_checklist($name, $selected);
}

/**
 * @deprecated in 0.1.6
 *
 */
function sp_breadcrumb($home, $sep, $prefix = '', $suffix = '', $display = true) {
	_deprecated_function( __FUNCTION__, '0.1.6', 'genesis_breadcrumb()' );
	
	if($display)
	genesis_breadcrumb($home, $sep, $prefix, $suffix, $display);
	else
	return genesis_breadcrumb($home, $sep, $prefix, $suffix, $display);
}

/**
 * @deprecated in 0.1.7
 *
 */
function genesis_get_timthumb($src = null, $w = null, $h = null, $zc = null) {
	_deprecated_function( __FUNCTION__, '0.1.7', 'genesis_get_image()' );
	
	return genesis_get_image(array('format' => 'src', 'size' => array($w, $h)));
}
function genesis_timthumb($src = null, $w = null, $h = null, $zc = null) {
	_deprecated_function( __FUNCTION__, '0.1.7', 'genesis_get_image()' );
	
	genesis_image(array('format' => 'src', 'size' => array($w, $h)));
}

/**
 * @deprecated in 1.1.2
 */
function genesis_before_respond() {
	_deprecated_function( __FUNCTION__, '1.1.2', 'genesis_before_comment_form()' );
	
	genesis_before_comment_form();
}
function genesis_after_respond() {
	_deprecated_function( __FUNCTION__, '1.1.2', 'genesis_after_comment_form()' );
	
	genesis_after_comment_form();
}

/**
 * @deprecated in 1.2
 */
function genesis_add_image_size($name, $width = 0, $height = 0, $crop = FALSE) {
	_deprecated_function( __FUNCTION__, '1.2', 'add_image_size()' );
	
	add_image_size($name, $width, $height, $crop);
}

/**
 * @deprecated in 1.2
 */
function genesis_add_intermediate_sizes($deprecated = '') {
	_deprecated_function( __FUNCTION__, '1.2' );
	
	return array();
}

/**
 * @deprecated in 1.2
 */
function genesis_comment() {
	_deprecated_function( __FUNCTION__, '1.2', 'genesis_after_comment()' );
	
	do_action('genesis_after_comment');
}