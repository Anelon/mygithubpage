<?php
/**
 * This function registers the default values for Genesis SEO Settings
 */
function genesis_seo_settings_defaults() {
	$defaults = array( // define our defaults
		'home_h1_on' => 'title',
		'home_description' => '',
		'home_keywords' => '',
		'append_description_home' => 1,
		'append_site_title' => 1,
		'doctitle_sep' => '—',
		'doctitle_seplocation' => 'right',
		'index_cat_archive' => 1,
		'index_tag_archive' => 0,
		'index_author_archive' => 0,
		'index_date_archive' => 0,
		'index_search_archive' => 0,
		'nofollow_home_link' => 1,
		'nofollow_comments_link' => 1,
		'nofollow_cat_link' => 0,
		'nofollow_tag_link' => 1,
		'nofollow_author_link' => 1,
		'enable_canonical' => 1 // <-- no comma after last option
	);
	
	return apply_filters('genesis_seo_settings_defaults', $defaults);
}

/**
 * This registers the settings field and adds defaults to the options table
 */
add_action('admin_init', 'genesis_register_seo_settings');
function genesis_register_seo_settings() {
	register_setting(GENESIS_SEO_SETTINGS_FIELD, GENESIS_SEO_SETTINGS_FIELD);
	add_option(GENESIS_SEO_SETTINGS_FIELD, genesis_seo_settings_defaults(), '', 'yes');
}

/**
 * This is the notice that displays when you successfully save or reset
 * the SEO settings.
 */
add_action('admin_notices', 'genesis_seo_settings_notice');
function genesis_seo_settings_notice() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'seo-settings' )
		return;
	
	if ( genesis_get_seo_option('reset') ) {
		update_option(GENESIS_SEO_SETTINGS_FIELD, genesis_seo_settings_defaults());
		echo '<div id="message" class="updated" id="message"><p><strong>'.__('SEO Settings Reset', 'genesis').'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {  
		echo '<div id="message" class="updated" id="message"><p><strong>'.__('SEO Settings Saved', 'genesis').'</strong></p></div>';
	}
	
}

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
add_action('admin_menu', 'genesis_seo_settings_init');
function genesis_seo_settings_init() {
	global $_genesis_seo_settings_pagehook;
	
	add_action('load-'.$_genesis_seo_settings_pagehook, 'genesis_seo_settings_scripts');
	add_action('load-'.$_genesis_seo_settings_pagehook, 'genesis_seo_settings_boxes');
}

function genesis_seo_settings_scripts() {	
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

function genesis_seo_settings_boxes() {
	global $_genesis_seo_settings_pagehook;
	
	add_meta_box('genesis-seo-settings-version', __('Doctitle Settings', 'genesis'), 'genesis_seo_settings_doctitle_box', $_genesis_seo_settings_pagehook, 'column1');
	add_meta_box('genesis-seo-settings-general', __('Homepage Settings', 'genesis'), 'genesis_seo_settings_homepage_box', $_genesis_seo_settings_pagehook, 'column1');
	add_meta_box('genesis-seo-settings-posts', __('Search Engine Indexing Settings', 'genesis'), 'genesis_seo_settings_indexing_box', $_genesis_seo_settings_pagehook, 'column2');
	add_meta_box('genesis-seo-settings-blogpage', __('Link nofollow Settings', 'genesis'), 'genesis_seo_settings_nofollow_box', $_genesis_seo_settings_pagehook, 'column2');
	add_meta_box('genesis-seo-settings-scripts', __('Canonical Tag Settings', 'genesis'), 'genesis_seo_settings_canonical_box', $_genesis_seo_settings_pagehook, 'column2');
}

/**
 * Tell WordPress that we want only 2 columns available for our meta-boxes
 */
add_filter('screen_layout_columns', 'genesis_seo_settings_layout_columns', 10, 2);
function genesis_seo_settings_layout_columns($columns, $screen) {
	global $_genesis_seo_settings_pagehook;
	if ($screen == $_genesis_seo_settings_pagehook) {
		// This page should only have 2 column options
		$columns[$_genesis_seo_settings_pagehook] = 2;
	}
	return $columns;
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, outputs necessary JS stuff, and fires <code>do_meta_boxes()</code>
 */
function genesis_seo_settings_admin() { 
global $_genesis_seo_settings_pagehook, $screen_layout_columns;
if( $screen_layout_columns == 3 ) {
	$width = 'width: 32.67%';
	$hide2 = $hide3 = ' display: block;';
}
elseif( $screen_layout_columns == 2 ) {
	$width = 'width: 49%;';
	$hide2 = ' display: block;';
	$hide3 = ' display: none;';
}
else {
	$width = 'width: 99%;';
	$hide2 = $hide3 = ' display: none;';
}
?>	
	<div id="genesis-seo-settings" class="wrap genesis-metaboxes">
	<form method="post" action="options.php">
		
		<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields(GENESIS_SEO_SETTINGS_FIELD); // important! ?>
		
		<?php screen_icon('options-general'); ?>
		<h2>
			<?php _e('Genesis - SEO Settings', 'genesis'); ?>
			<input type="submit" class="button-primary add-new-h2" value="<?php _e('Save Settings', 'genesis') ?>" />
			<input type="submit" class="button-highlighted add-new-h2" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'genesis'); ?>" onclick="return genesis_confirm('<?php echo esc_js( __('Are you sure you want to reset?', 'genesis') ); ?>');" />
		</h2>
		
		<div class="metabox-holder">
			<div class="postbox-container" style="<?php echo $width; ?>">
				<?php do_meta_boxes($_genesis_seo_settings_pagehook, 'column1', null); ?>
			</div>
			<div class="postbox-container" style="<?php echo $width; echo $hide2; ?>">
				<?php do_meta_boxes($_genesis_seo_settings_pagehook, 'column2', null); ?>
			</div>
		</div>
		
		<div class="bottom-buttons">
			<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'genesis') ?>" />
			<?php $reset_onclick = 'onclick="if ( confirm(\'' . esc_js( __('Are you sure you want to reset?', 'genesis') ) . '\') ) {return true;}return false;"'; ?>
			<input type="submit" <?php echo $reset_onclick; ?> class="button-highlighted" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'genesis'); ?>" />
		</div>
	</form>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_genesis_seo_settings_pagehook; ?>');
		});
		//]]>
	</script>

<?php
}

/**
 * This next section defines functions that contain the content of the "boxes" that will be
 * output by default on the "SEO Settings" page. There's a bunch of them.
 *
 */
function genesis_seo_settings_doctitle_box() { ?>
	<p><span class="description"><?php _e('The Document Title is the single most important SEO tag in your document source. It succinctly informs search engines of what information is contained in the document. The doctitle changes from page to page, but these options will help you control what it looks by default.', 'genesis'); ?></span></p>
	<p><span class="description"><?php _e('By default, the homepage doctitle will contain the site title, the single post and page doctitle will contain the post/page title, archive pages will contain the archive type, etc.', 'genesis'); ?></span></p>
	<p><label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[append_description_home]" value="1" <?php checked(1, genesis_get_seo_option('append_description_home')); ?> /> <?php _e('Append Site Description to Doctitle on homepage?', 'genesis'); ?></label></p>
	<p><label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[append_site_title]" value="1" <?php checked(1, genesis_get_seo_option('append_site_title')); ?> /> <?php _e('Append Site Name to Doctitle on inner pages?', 'genesis'); ?> </label></p>
	<p><?php _e('Doctitle (<code>&lt;title&gt;</code>) Append Location', 'genesis'); ?>:<br />
	<span class="description"><?php _e('Determines what side the appended doctitle text will go on', 'genesis'); ?></span></p>
	<p><label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[doctitle_seplocation]" value="left" <?php checked('left', genesis_get_seo_option('doctitle_seplocation')); ?> />
	<?php _e('Left', 'genesis'); ?></label>
	<label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[doctitle_seplocation]" value="right" <?php checked('right', genesis_get_seo_option('doctitle_seplocation')); ?> />
	<?php _e('Right', 'genesis'); ?></label></p>
	<p><?php _e('Doctitle (<code>&lt;title&gt;</code>) Separator', 'genesis'); ?>:
	<input type="text" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[doctitle_sep]" value="<?php echo esc_attr( genesis_get_seo_option('doctitle_sep') ); ?>" size="15" /></p>
	<p><span class="description"><?php _e('<b>NOTE:</b> If the doctitle consists of two parts (Title &amp; Appended Text), then the Doctitle Separator will go between them.', 'genesis'); ?></span></p>
<?php
}

function genesis_seo_settings_homepage_box() { ?>
	<p><?php printf(__('Which text would you like to be wrapped in %s tags?', 'genesis'), '<code>&lt;h1&gt;</code>'); ?><br />
	<span class="description"><?php printf(__('The %s tag is the second most important SEO tag in the document source. Choose wisely.', 'genesis'), '<code>&lt;h1&gt;</code>'); ?></span><br /></p>
	<p><label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="title" <?php checked('title', genesis_get_seo_option('home_h1_on')); ?> />
	<?php _e('Site Title', 'genesis'); ?></label><br />
	<label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="description" <?php checked('description', genesis_get_seo_option('home_h1_on')); ?> />
	<?php _e('Site Description', 'genesis'); ?></label><br />
	<label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="neither" <?php checked('neither', genesis_get_seo_option('home_h1_on')); ?> />
	<?php _e('Neither. I\'ll manually wrap my own text on the homepage', 'genesis'); ?></label></p>
	<p><?php _e('Home META Description', 'genesis'); ?>:<br />
	<textarea name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[home_description]" rows="3" cols="34"><?php echo htmlspecialchars( genesis_get_seo_option('home_description') ); ?></textarea></p>
	<p><span class="description"><?php _e('<b>NOTE:</b> The META Description is usually used to determine the text used under the title on search engine results pages.', 'genesis'); ?></span></p>
	<p><?php _e('Home META Keywords (comma separated)', 'genesis'); ?>:<br />
	<input type="text" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[home_keywords]" value="<?php echo esc_attr( genesis_get_seo_option('home_keywords') ); ?>" size="40" /></p>
	<p><span class="description"><?php _e('<b>NOTE:</b> META Keywords can suggest what content is present on a site, but are generally ignored by Search Engines.', 'genesis'); ?></span></p>
<?php
}

function genesis_seo_settings_indexing_box() { ?>
	<p><span class="description"><?php _e('Depending on your situation, you may or may not want the following archive pages to be indexed by search engines. Only you can make that determination.', 'genesis'); ?></span></p>
	<p><?php _e('Select the following archives you would like search engines to index:', 'genesis'); ?></p>
	<p><label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[index_cat_archive]" value="1" <?php checked(1, genesis_get_seo_option('index_cat_archive')); ?> /> <?php _e('Index Category Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[index_tag_archive]" value="1" <?php checked(1, genesis_get_seo_option('index_tag_archive')); ?> /> <?php _e('Index Tag Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[index_author_archive]" value="1" <?php checked(1, genesis_get_seo_option('index_author_archive')); ?> /> <?php _e('Index Author Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[index_date_archive]" value="1" <?php checked(1, genesis_get_seo_option('index_date_archive')); ?> /> <?php _e('Index Date Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[index_search_archive]" value="1" <?php checked(1, genesis_get_seo_option('index_search_archive')); ?> /> <?php _e('Index Search Results', 'genesis'); ?>?</label></p>
<?php
}

function genesis_seo_settings_nofollow_box() { ?>
	<p><?php _e('Select the links you would like to nofollow:', 'genesis'); ?></p>
	<p><label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[nofollow_home_link]" value="1" <?php checked(1, genesis_get_seo_option('nofollow_home_link')); ?> /> <?php _e('nofollow Home Link', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[nofollow_comments_link]" value="1" <?php checked(1, genesis_get_seo_option('nofollow_comments_link')); ?> /> <?php _e('nofollow Comments Link', 'genesis'); ?>?</label></p>
	<p><span class="description"><?php _e('<b>NOTE:</b> If you have chosen not to index certain archives, it also makes sense to apply the nofollow tag to any links to those archives.', 'genesis'); ?></span></p>
	<p><label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[nofollow_cat_link]" value="1" <?php checked(1, genesis_get_seo_option('nofollow_cat_link')); ?> /> <?php _e('nofollow Links to Category Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[nofollow_tag_link]" value="1" <?php checked(1, genesis_get_seo_option('nofollow_tag_link')); ?> /> <?php _e('nofollow Links to Tag Archives', 'genesis'); ?>?</label><br />
	<label><input type="checkbox" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[nofollow_author_link]" value="1" <?php checked(1, genesis_get_seo_option('nofollow_author_link')); ?> /> <?php _e('nofollow Links to Author Archives', 'genesis'); ?>?</label></p>
<?php
}

function genesis_seo_settings_canonical_box() { ?>
	<p><?php _e('Would you like to enable use of the canonical tag?', 'genesis'); ?></p>
	<p><label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[enable_canonical]" value="1" <?php checked(1, genesis_get_seo_option('enable_canonical')); ?> />
	<?php _e('Yes', 'genesis'); ?></label>
	<label><input type="radio" name="<?php echo GENESIS_SEO_SETTINGS_FIELD; ?>[enable_canonical]" value="0" <?php checked(0, genesis_get_seo_option('enable_canonical')); ?> />
	<?php _e('No', 'genesis'); ?></label></p>
	<p><span class="description"><?php _e('<b>NOTE:</b> The Canonical Tag is used to inform search engines of the proper URL to index when they crawl your site.', 'genesis'); ?></span></p>
<?php
}