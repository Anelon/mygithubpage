<?php

/**
 * Primary Sidebar Content
 */
add_action('genesis_sidebar', 'genesis_do_sidebar');
function genesis_do_sidebar() {

	if (!dynamic_sidebar('Sidebar')) : ?>
	
	<div class="widget widget_text"><div class="widget-wrap">
		<h4 class="widgettitle"><?php _e('Sidebar Widget Area', 'genesis'); ?></h4>
		<div class="textwidget"><p><?php printf(__('This is the Sidebar Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'genesis'), admin_url('widgets.php')); ?></p></div>
	</div></div>
		
	<?php endif;
	
}

/**
 * Alternate Sidebar Content
 */
add_action('genesis_sidebar_alt', 'genesis_do_sidebar_alt');
function genesis_do_sidebar_alt() {

	if (!dynamic_sidebar('Sidebar Alt')) : ?>
	
	<div class="widget widget_text"><div class="widget-wrap">
		<h4 class="widgettitle"><?php _e('Sidebar-Alt Widget Area', 'genesis'); ?></h4>
		<div class="textwidget"><p><?php printf(__('This is the Sidebar-Alt Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'genesis'), admin_url('widgets.php')); ?></p></div>
	</div></div>
		
	<?php endif;
	
}