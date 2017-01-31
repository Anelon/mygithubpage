<?php
/*
 * @todo Document this file
 */

function genesis_purchase_themes_admin() { ?>
	
<div class="wrap purchase-themes">
	<?php screen_icon('themes'); ?>
	<h2><?php _e('Genesis - Purchase Themes', 'genesis'); ?></h2>
	
	<div class="metabox-holder">
	<?php
		$store = get_transient('genesis-remote-store');
		
		if ( !$store ) {
			$store = wp_remote_retrieve_body( wp_remote_request( 'http://www.studiopress.com/store.array.php' ) );
			set_transient('genesis-remote-store', maybe_unserialize( $store ), 60*60*12); // store for 12 hours
		}
		
		foreach ( (array)$store as $product ) { ?>
			<div class="postbox">
			<h3><?php echo esc_html( $product['title'] ); ?></h3>
				<div class="inside">
					<p><img src="<?php echo esc_url( $product['img'] ); ?>" width="235" height="180" /></p>
					<p align="center"><a class="button-secondary thickbox thickbox-preview" href="<?php echo esc_url( $product['preview'] ); ?>?preview=1&amp;TB_iframe=true"><?php _e('Preview Theme', 'genesis'); ?></a> <a target="_blank" class="button-secondary" href="<?php echo esc_url( $product['purchase'] ) ?>"><?php _e('Purchase Theme', 'genesis'); ?></a></p>
				</div>
			</div>
		<?php }
	
	?>
	</div>
	
</div>
<?php }