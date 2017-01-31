<?php

/*
Plugin Name: Add Your Own Headers
Plugin URI: http://wp.uberdose.com/2007/03/30/add-your-own-headers/
Description: Plugin for inserting your own headers into posts.
Version: 0.4.1
Author: uberdose
Author URI: http://wp.uberdose.com/
*/

/* Copyright (C) 2010 uberdose (wordpress AT uberdose DOT com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA */
 
 class Add_Your_Own_Headers {
 	
 	var $version = "0.4.1";

	function Add_Your_Own_Headers() {
		global $wp_version;
		$this->wp_version = $wp_version;
	}

	function init() {
		if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('add_your_own_headers', 'wp-content/plugins/add-your-own-headers');
		}
	}

	 function wp_head() {
		global $post;
		$meta_string = null;
		
		if (is_single() || is_page()) {
			echo "\n<!-- add_your_own_headers $this->version -->\n";
		
			// custom tags
        		$custom_001 = stripslashes(get_post_meta($post->ID, "custom_header_001", true));
        		$custom_002 = stripslashes(get_post_meta($post->ID, "custom_header_002", true));
			if (isset ($custom_001) && !empty($custom_001)) {
				$meta_string .= sprintf("%s\n", $custom_001);
			}
			if (isset ($custom_002) && !empty($custom_002)) {
				$meta_string .= sprintf("%s\n", $custom_002);
			}

			if ($meta_string != null) {
				echo $meta_string;
			}
		}
	}
	
	function add_headers_textinput() {
	    global $post;
	    $custom_tag_001 = htmlspecialchars(get_post_meta($post->ID, 'custom_header_001', true));
	    $custom_tag_002 = htmlspecialchars(get_post_meta($post->ID, 'custom_header_002', true));
		?>

		<?php if (substr($this->wp_version, 0, 3) == '2.5') { ?>
		<div id="postayoh" class="postbox closed">
		<h3><?php _e('Add Your Own Headers', 'add_your_own_headers') ?></h3>
		<div class="inside">
		<div id="postayoh">
		<?php } else { ?>
		<div class="dbx-b-ox-wrapper">
		<fieldset id="ayohdiv" class="dbx-box">
		<div class="dbx-h-andle-wrapper">
		<h3 class="dbx-handle"><?php _e('Add Your Own Headers', 'add_your_own_headers') ?></h3>
		</div>
		<div class="dbx-c-ontent-wrapper">
		<div class="dbx-content">
		<?php } ?>

		<form name="dofollow" action="" method="post">
		<input value="ayoh_edit" type="hidden" name="ayoh_edit" />
		<table style="margin-bottom:40px; margin-top:30px;">
		<tr><th style="text-align:left;" colspan="2"><a title="Homepage for Add Your Own Headers" target="__blank" href="http://wp.uberdose.com/2007/03/30/add-your-own-headers/">Click here for Support</a></th></tr>
		<tr>
		<th scope="row" style="text-align:right;"><?php _e('Custom Header:') ?></th>
		<td><input value="<?php echo $custom_tag_001 ?>" type="text" name="custom_header_001" size="50"/></td>
		</tr>
		<tr>
		<th scope="row" style="text-align:right;"><?php _e('Another Custom Header:') ?></th>
		<td><input value="<?php echo $custom_tag_002 ?>" type="text" name="custom_header_002" size="50"/></td>
		</tr>
		</table>
		</form>

		<?php if (substr($this->wp_version, 0, 3) == '2.5') { ?>
		</div></div></div>
		<?php } else { ?>
		<?php } ?>

		<?php
	}

	function post_headers($id) {
	    $ayoh_edit = $_POST["ayoh_edit"];
	    if (isset($ayoh_edit) && !empty($ayoh_edit)) {
		    $custom_tag_001 = stripslashes($_POST["custom_header_001"]);
		    $custom_tag_002 = stripslashes($_POST["custom_header_002"]);

		    delete_post_meta($id, 'custom_header_001');
		    delete_post_meta($id, 'custom_header_002');

		    if (isset($custom_tag_001) && !empty($custom_tag_001)) {
			    add_post_meta($id, 'custom_header_001', $custom_tag_001);
		    }
		    if (isset($custom_tag_002) && !empty($custom_tag_002)) {
			    add_post_meta($id, 'custom_header_002', $custom_tag_002);
		    }
	    }
	}

	// not used at the moment ...
	function admin_menu() {
		add_submenu_page('options-general.php', __('Add your own headers'), __('Add your own headers'), 5, __FILE__, array($this, 'plugin_menu'));
	}
			
			function plugin_menu() {
				$message = null;
				$message_updated = __("Settings updated.");
				
				// update options
				if ($_POST['action'] && $_POST['action'] == 'update_ayoh_settings') {
					$message = $message_updated;
					update_option('ayoh_headers_on_homepage', $_POST['ayoh_headers_on_homepage']);
					wp_cache_flush();
				}
				
			?>
<?php if ($message) : ?>
<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div id="dropmessage" class="updated" style="display:none;"></div>
<div class="wrap">
<h2><?php _e('Add your own headers Options'); ?></h2>
<p><?php _e('For feedback, help etc. please click <a title="Homepage for Add your own headers" href="http://wp.uberdose.com/2007/03/30/add-your-own-headers/#respond">here</a>.') ?></p>
<form name="dofollow" action="" method="post">
<table>
<tr>
<th scope="row" style="text-align:right; vertical-align:top;"><?php _e('Show headers on homepage:')?></th>
<td>
<input type="checkbox" name="ayoh_headers_on_homepage" <?php if (get_option('ayoh_headers_on_homepage')) echo "checked=\"1\""; ?>/></input></td>
</tr>
</table>
<p class="submit">
<input type="hidden" name="action" value="update_ayoh_settings" /> 
<input type="submit" name="Submit" value="<?php _e('Update Options')?> &raquo;" /> 
</p>
</form>
</div>
<?php
	
	} // plugin_menu

} // Add_Your_Own_Headers

$_ayoh = new Add_Your_Own_Headers();

//add_option("ayoh_headers_on_homepage", null, __('Show headers on hommepage'), 'yes');

add_action('init', array($_ayoh, 'init'));
add_action('wp_head', array($_ayoh, 'wp_head'));

//add_action('admin_menu', array($_ayoh, 'admin_menu'));

add_action('simple_edit_form', array($_ayoh, 'add_headers_textinput'));
add_action('edit_form_advanced', array($_ayoh, 'add_headers_textinput'));
add_action('edit_page_form', array($_ayoh, 'add_headers_textinput'));

add_action('edit_post', array($_ayoh, 'post_headers'));
add_action('publish_post', array($_ayoh, 'post_headers'));
add_action('save_post', array($_ayoh, 'post_headers'));
add_action('edit_page_form', array($_ayoh, 'post_headers'));

?>
