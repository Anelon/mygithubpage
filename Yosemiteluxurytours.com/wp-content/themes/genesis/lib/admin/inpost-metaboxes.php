<?php
/**
 * This file outputs the Genesis-specific inpost option boxes.
 * It also handles saving the user input from those boxes, when a
 * post or page gets published or updated.
 *
 */

/**
 * This code adds a new box to the post/page edit screen,
 * so that the user can set SEO options on a per-post/page basis.
 *
 * genesis_add_inpost_seo_box() is used to register the boxes.
 * @uses add_meta_box
 * genesis_inpost_seo_box() generates the content in the boxes.
 * @uses wp_create_nonce, checked, genesis_get_custom_field
 *
 * @since 0.1.3
 */
add_action('admin_menu', 'genesis_add_inpost_seo_box');
function genesis_add_inpost_seo_box() {
	
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-seo' ) || $type == 'post' || $type = 'page' ) {
			add_meta_box('genesis_inpost_seo_box', __('Genesis SEO Options and Settings', 'genesis'), 'genesis_inpost_seo_box', $type, 'normal', 'high');
		}	
	}
	
}

function genesis_inpost_seo_box() { ?>
	
	<input type="hidden" name="genesis_inpost_seo_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	
	<p><label for="genesis_title"><b><?php _e('Custom Document Title', 'genesis'); ?></b> <abbr title="&lt;title&gt; Tag">[?]</abbr></label></p>
	<p><input style="width: 99%;" type="text" name="genesis_seo[_genesis_title]" id="genesis_title" value="<?php echo esc_attr( genesis_get_custom_field('_genesis_title')); ?>" /></p>
	
	<p><label for="genesis_description"><b><?php _e('Custom Post/Page Meta Description', 'genesis'); ?></b> <abbr title="&lt;meta name=&quot;description&quot; /&gt;">[?]</abbr></label></p>
	<p><textarea style="width: 99%;" name="genesis_seo[_genesis_description]" id="genesis_description"><?php echo htmlspecialchars(genesis_get_custom_field('_genesis_description')); ?></textarea></p>
	
	<p><label for="genesis_keywords"><b><?php _e('Custom Post/Page Meta Keywords, comma separated', 'genesis'); ?></b> <abbr title="&lt;meta name=&quot;keywords&quot; /&gt;">[?]</abbr></label></p>
	<p><input style="width: 99%;" type="text" name="genesis_seo[_genesis_keywords]" id="genesis_keywords" value="<?php echo esc_attr(genesis_get_custom_field('_genesis_keywords')); ?>" /></p>
	
	<p><label for="genesis_canonical"><b><?php _e('Custom Canonical URI', 'genesis'); ?></b> <a href="http://www.mattcutts.com/blog/canonical-link-tag/" target="_blank" title="&lt;link rel=&quot;canonical&quot; /&gt;">[?]</a></label></p>
	<p><input style="width: 99%;" type="text" name="genesis_seo[_genesis_canonical_uri]" id="genesis_canonical" value="<?php echo esc_attr(genesis_get_custom_field('_genesis_canonical_uri')); ?>" /></p>
	
	<br />
	
	<p><b><?php _e('Robots Meta Settings', 'genesis'); ?></b></p>
	
	<p>
		<input type="checkbox" name="genesis_seo[_genesis_noindex]" id="genesis_noindex" value="1" <?php checked(1, genesis_get_custom_field('_genesis_noindex')); ?> /> 
		<label for="genesis_noindex"><?php printf( __('Apply %s to this post/page', 'genesis'), '<code>noindex</code>' ); ?> <a href="http://www.robotstxt.org/meta.html" target="_blank">[?]</a></label><br />
		
		<input type="checkbox" name="genesis_seo[_genesis_nofollow]" id="genesis_nofollow" value="1" <?php checked(1, genesis_get_custom_field('_genesis_nofollow')); ?> /> 
		<label for="genesis_nofollow"><?php printf( __('Apply %s to this post/page', 'genesis'), '<code>nofollow</code>' ); ?> <a href="http://www.robotstxt.org/meta.html" target="_blank">[?]</a></label><br />
	
		<input type="checkbox" name="genesis_seo[_genesis_noarchive]" id="genesis_noarchive" value="1" <?php checked(1, genesis_get_custom_field('_genesis_noarchive')); ?> /> 
		<label for="genesis_nofollow"><?php printf( __('Apply %s to this post/page', 'genesis'), '<code>noarchive</code>' ); ?> <a href="http://www.ezau.com/latest/articles/no-archive.shtml" target="_blank">[?]</a></label>
	</p>
	
	<br />
	
	<p><label for="genesis_scripts"><b><?php _e('Custom Tracking/Conversion Code', 'genesis'); ?></b></label></p>
	<p><textarea style="width: 99%;" rows="4" name="genesis_seo[_genesis_scripts]" id="genesis_scripts"><?php echo htmlspecialchars(genesis_get_custom_field('_genesis_scripts')); ?></textarea></p>

<?php
}

/**
 * This function saves the SEO settings when we save a post/page.
 * It does so by grabbing the array passed in $_POST, looping through
 * it, and saving each key/value pair as a custom field.
 *
 * @uses wp_verify_nonce, plugin_basename, current_user_can
 * @uses add_post_meta, delete_post_meta, get_custom_field
 *
 * @since 0.1.3
 */
add_action('save_post', 'genesis_inpost_seo_save', 1, 2);
function genesis_inpost_seo_save($post_id, $post) {
	
	//	verify the nonce
	if ( !isset($_POST['genesis_inpost_seo_nonce']) || !wp_verify_nonce( $_POST['genesis_inpost_seo_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
		
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;

	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
		
	// Define all as false, to be trumped by user submission
	$seo_post_defaults = array(
		'_genesis_title' => '',
		'_genesis_description' => '',
		'_genesis_keywords' => '',
		'_genesis_canonical_uri' => '',
		'_genesis_noindex' => 0, 
		'_genesis_nofollow' => 0,
		'_genesis_noarchive' => 0,
		'_genesis_scripts' => ''
	); 

	$genesis_seo = wp_parse_args($_POST['genesis_seo'], $seo_post_defaults);
	
	//	store the custom fields
	foreach ( (array)$genesis_seo as $key => $value ) {
		
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		
		//	sanitize the title, description, and tags before storage
		if ( $key == '_genesis_title' || $key == '_genesis_description' || $key == '_genesis_keywords' )
			$value = esc_html( strip_tags( $value ) );
		
		if ( $value ) {
			//	save/update
			update_post_meta($post->ID, $key, $value);
		} else {
			//	delete if blank
			delete_post_meta($post->ID, $key);
		}

	}
}

/**
 * This code adds a new box to the post/page edit screen,
 * so that the user can set layout options on a per-post/page basis.
 *
 * genesis_add_inpost_layout_box() is used to register the boxes.
 * @uses add_meta_box
 * genesis_inpost_layout_box() generates the content in the boxes.
 * @uses wp_create_nonce, checked, genesis_get_custom_field
 *
 * @since 0.2.2
 */
add_action('admin_menu', 'genesis_add_inpost_layout_box');
function genesis_add_inpost_layout_box() {
	
	if ( !current_theme_supports('genesis-inpost-layouts') )
		return;
	
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) || $type == 'post' || $type = 'page' ) {
			add_meta_box('genesis_inpost_layout_box', __('Genesis Layout Options', 'genesis'), 'genesis_inpost_layout_box', $type, 'normal', 'high');
		}	
	}

}
function genesis_inpost_layout_box() { ?>

	<?php wp_nonce_field( plugin_basename(__FILE__), 'genesis_inpost_layout_nonce' ); ?>
	
	<?php $_genesis_layout = genesis_get_custom_field('_genesis_layout'); ?>
	
	<input type="radio" name="_genesis_layout" id="default-layout" value="" <?php checked('', $_genesis_layout); ?> /> <label class="default" for="default-layout"><?php printf(__('Default Layout set in <a href="%s">Theme Settings</a>', 'genesis'), admin_url('admin.php?page=genesis')); ?></label>
	
	<br style="clear: both;" /><br />
	
	<label class="box"><input type="radio" name="_genesis_layout" id="content-sidebar" value="content-sidebar" <?php checked('content-sidebar', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/cs.gif" alt="" /></label>
	
	<label class="box"><input type="radio" name="_genesis_layout" id="sidebar-content" value="sidebar-content" <?php checked('sidebar-content', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/sc.gif" alt="" /></label>
	
	<label class="box"><input type="radio" name="_genesis_layout" id="content-sidebar-sidebar" value="content-sidebar-sidebar" <?php checked('content-sidebar-sidebar', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/css.gif" alt="" /></label>
	
	<label class="box"><input type="radio" name="_genesis_layout" id="sidebar-sidebar-content" value="sidebar-sidebar-content" <?php checked('sidebar-sidebar-content', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/ssc.gif" alt="" /></label>
	
	<label class="box"><input type="radio" name="_genesis_layout" id="sidebar-content-sidebar" value="sidebar-content-sidebar" <?php checked('sidebar-content-sidebar', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/scs.gif" alt="" /></label>
		
	<label class="box"><input style="float: left; margin-right: 5px;" type="radio" name="_genesis_layout" id="full-width-content" value="full-width-content" <?php checked('full-width-content', $_genesis_layout); ?> /> <img src="<?php echo GENESIS_ADMIN_IMAGES_URL; ?>/layouts/c.gif" alt="Full Width Content" /></label>
	
	<br style="clear: both;" />
	
<?php
}

/**
 * This function saves the layout options when we save a post/page.
 * It does so by grabbing the array passed in $_POST, looping through
 * it, and saving each key/value pair as a custom field.
 *
 * @uses wp_verify_nonce, plugin_basename, current_user_can
 * @uses add_post_meta, delete_post_meta, get_custom_field
 *
 * @since 0.2.2
 */
add_action('save_post', 'genesis_inpost_layout_save', 1, 2);
function genesis_inpost_layout_save($post_id, $post) {
	
	//	verify the nonce
	if ( !isset($_POST['genesis_inpost_layout_nonce']) || !wp_verify_nonce( $_POST['genesis_inpost_layout_nonce'], plugin_basename(__FILE__) ) )
		return $post_id;
		
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;

	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id)) || !current_user_can('edit_post', $post_id ) )
		return $post_id;
		
	$genesis_layout = $_POST['_genesis_layout'];
	
	if ( $genesis_layout ) {
		//	save/update
		update_post_meta($post_id, '_genesis_layout', $genesis_layout);
	} else {
		//	delete if blank
		delete_post_meta($post_id, '_genesis_layout');
	}
	
}