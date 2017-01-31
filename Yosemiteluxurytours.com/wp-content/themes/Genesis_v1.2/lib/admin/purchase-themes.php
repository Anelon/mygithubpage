<?php
/*
 * @todo Document this file
 */

function genesis_purchase_themes_admin() { ?>
	
<div class="wrap purchase-themes">
	<?php screen_icon('themes'); ?>
	<h2><?php _e('Genesis - Purchase Themes', 'genesis'); ?></h2>
	
	<?php
		$store = get_transient('genesis-remote-store');
		
		if(!$store) {
			$store = wp_remote_retrieve_body(wp_remote_request('http://www.studiopress.com/store.php'));
			set_transient('genesis-remote-store', $store, 60*60*12); // store for 12 hours
		}
		
		echo $store;
	?>
	
</div>
<?php }