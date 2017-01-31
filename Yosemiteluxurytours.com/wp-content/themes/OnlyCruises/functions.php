<?php
// Start the engine
require_once(TEMPLATEPATH.'/lib/init.php');

// Add new image sizes
genesis_add_image_size('Rectangular', 280, 125, TRUE);
genesis_add_image_size('Square', 90, 90, TRUE);
genesis_add_image_size('Slider', 900, 300, TRUE);

// Load script for jFlow slider
add_action('get_header', 'serenity_load_scripts');
function serenity_load_scripts() {
    wp_enqueue_script('jflow', CHILD_URL.'/js/jquery.flow.1.1.js', array('jquery'), '1.1', TRUE);
}

// Load parameters for jFlow slider
add_action('wp_footer', 'serenity_jflow_params');
function serenity_jflow_params() {
	$timer = intval(genesis_get_option('slider_timer'));
	$duration = intval(genesis_get_option('slider_duration'));
	
	 $output = '
		jQuery(document).ready(function($) { 
			$("div#controller").jFlow({
				slides: "#slides", 
				width: "900px", 
				height: "300px", 
				timer: '.$timer.', 
				duration: '.$duration.' 
			});
		});
	';
	$output = str_replace(array("\n","\t","\r"), '', $output);
	
	echo '<script type=\'text/javascript\'>'.$output.'</script>';
}

// Add the slider on the homepage above the content area
add_action('genesis_after_header', 'serenity_include_slider'); 
function serenity_include_slider() {
    if(is_front_page() && genesis_get_option('slider_enable'))
    require(CHILD_DIR.'/slider.php');
}

// Add two sidebars to the main sidebar area
add_action('genesis_after_sidebar_widget_area', 'serenity_include_bottom_sidebars'); 
function serenity_include_bottom_sidebars() {
    require(CHILD_DIR.'/sidebar-bottom.php');
}

// Add Google AdSense after single post
add_action('genesis_after_post_content', 'serenity_include_adsense', 9); 
function serenity_include_adsense() {
    if(is_single())
    require(CHILD_DIR.'/adsense.php');
}

// Add widgeted footer section
add_action('genesis_after_footer', 'serenity_include_footer_widgets'); 
function serenity_include_footer_widgets() {
    require(CHILD_DIR.'/footer-widgeted.php');
}

// Customize Footer
add_filter('genesis_footer_creds_text', 'custom_footer_creds_text');
function custom_footer_creds_text($creds) {
 $creds = '[footer_copyright]&nbsp;' . get_bloginfo('name') . ' &bull; All Rights Reserved';
 return $creds;
}



// Force layout on homepage
add_filter('genesis_options', 'serenity_home_layout', 10, 2);
function serenity_home_layout($options, $setting) {
	if($setting == GENESIS_SETTINGS_FIELD) {
		if(is_home())
		$options['site_layout'] = 'content-sidebar';
	}
	return $options;
}

add_filter('get_the_excerpt', 'trim_excerpt');
function trim_excerpt($text) {
    return str_replace(' [...]', '... <a href="' . get_permalink() . '"> [Continue Reading]</a>', $text);
};

// Add new box to the Genesis -> Theme Settings page
add_action('admin_menu', 'serenity_add_settings_boxes', 11);
function serenity_add_settings_boxes() {
	global $_genesis_theme_settings_pagehook;
	
	add_meta_box('genesis-theme-settings-slider', __('Slider Settings', 'serenity'), 'serenity_theme_settings_slider_box', $_genesis_theme_settings_pagehook, 'column2');
}
function serenity_theme_settings_slider_box() { ?>
	<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_enable]" value="1" <?php checked(1, genesis_get_option('slider_enable')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_enable]"><?php _e('Enable the Slider?', 'serenity'); ?></label></p>
	
	<p><label><?php _e('Category', 'serenity'); ?>: <?php wp_dropdown_categories(array('name' => GENESIS_SETTINGS_FIELD.'[slider_cat]', 'selected' => genesis_get_option('slider_cat'), 'orderby' => 'Name' , 'hierarchical' => 1, 'show_option_all' => __("All Categories", 'serenity'), 'hide_empty' => '0')); ?></label></p>
	
	<p><label><?php _e('Number of Posts', 'serenity'); ?>: <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_num]" value="<?php genesis_option('slider_num'); ?>" size="5" /></label></p>
	
	<p><label><?php _e('Time Between Slides (in milliseconds)', 'serenity'); ?>: <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_timer]" value="<?php genesis_option('slider_timer'); ?>" size="5" /></label></p>
	
	<p><label><?php _e('Slide Transition Speed (in milliseconds)', 'serenity'); ?>: <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[slider_duration]" value="<?php genesis_option('slider_duration'); ?>" size="5" /></label></p>
<?php
}

// Add new default values for the slider
add_filter('genesis_theme_settings_defaults', 'serenity_slider_defaults');
function serenity_slider_defaults($defaults) {
	$defaults['slider_enable'] = 1;
	$defaults['slider_num'] = 1;
	$defaults['slider_timer'] = 6000;
	$defaults['slider_duration'] = 400;
	
	return $defaults;
}

// Register sidebars
genesis_register_sidebar(array(
	'name'=>'Sidebar Bottom Left',
	'description' => 'This is the bottom left column in the sidebar.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Sidebar Bottom Right',
	'description' => 'This is the bottom right column in the sidebar.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Featured Top Left',
	'description' => 'This is the featured top left column of the homepage.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Featured Top Right',
	'description' => 'This is the featured top right column of the homepage.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Featured Bottom',
	'description' => 'This is the featured bottom section of the homepage.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Footer #1',
	'description' => 'This is the first column of the footer section.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Footer #2',
	'description' => 'This is the second column of the footer section.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));
genesis_register_sidebar(array(
	'name'=>'Footer #3',
	'description' => 'This is the third column of the footer section.',
	'before_title'=>'<h4 class="widgettitle">','after_title'=>'</h4>'
));


?>