<?php
/**
 * This function, hooked to display on the category/tag edit forms,
 * adds new fields for SEO. The variables $tag and $taxonomy are passed
 * via the hook so that we can use them.
 */
add_action('category_edit_form', 'genesis_taxonomy_seo_options', 10, 2);
add_action('post_tag_edit_form', 'genesis_taxonomy_seo_options', 10, 2);
function genesis_taxonomy_seo_options($tag, $taxonomy) {
	//print_r($tag); // for debugging
?>

	<h3><?php _e('Genesis SEO Options and Settings', 'genesis'); ?></h3>
	<table class="form-table">

	<tr>
		<th scope="row" valign="top"><label><?php _e('Display Title/Description', 'genesis'); ?></label></th>
		<td>
			<label><input name="meta[display_title]" type="checkbox" value="1" <?php checked(1, $tag->meta['display_title']); ?> /> <?php printf( __('Display %s title at the top of archive pages?', 'genesis'), esc_html( $taxonomy ) ); ?></label><br />
			<label><input name="meta[display_description]" type="checkbox" value="1" <?php checked(1, $tag->meta['display_description']); ?> /> <?php printf( __('Display %s description at the top of archive pages?', 'genesis'), esc_html( $taxonomy ) ); ?></label>
		</td>
	</tr>
		
	<tr class="form-field">
		<th scope="row" valign="top"><label for="meta[doctitle]"><?php printf( __('Custom Document %s', 'genesis'), '<code>&lt;title&gt;</code>' ); ?></label></th>
		<td><input name="meta[doctitle]" id="meta[doctitle]" type="text" value="<?php echo esc_html( $tag->meta['doctitle'] ); ?>" size="40" />
		<p class="description"><?php _e('', 'genesis'); ?></p></td>
	</tr>
		
	<tr class="form-field">
		<th scope="row" valign="top"><label for="meta[description]"><?php printf( __('%s Description', 'genesis'), '<code>META</code>' ); ?></label></th>
		<td><textarea name="meta[description]" id="meta[description]" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( $tag->meta['description'] ); ?></textarea><br />
		<span class="description"><?php _e('Please limit to 156 characters', 'genesis'); ?></span></td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><label for="meta[keywords]"><?php printf( __('%s Keywords', 'genesis'), '<code>META</code>' ); ?></label></th>
		<td><input name="meta[keywords]" id="meta[keywords]" type="text" value="<?php echo esc_html( $tag->meta['keywords'] ); ?>" size="40" />
		<p class="description"><?php _e('Comma separated list', 'genesis'); ?></p></td>
	</tr>
	
	<tr>
		<th scope="row" valign="top"><label for="meta[noindex]"><?php _e('Noindex', 'genesis'); ?></label></th>
		<td><label><input name="meta[noindex]" id="meta[noindex]" type="checkbox" value="1" <?php checked(1, $tag->meta['noindex']); ?> /> <?php printf( __('Apply %s to this archive?', 'genesis'), '<code>noindex</code>' ); ?></label></td>
	</tr>
	
	<tr>
		<th scope="row" valign="top"><label for="meta[nofollow]"><?php _e('Nofollow', 'genesis'); ?></label></th>
		<td><label><input name="meta[nofollow]" id="meta[nofollow]" type="checkbox" value="1" <?php checked(1, $tag->meta['nofollow']); ?> /> <?php printf( __('Apply %s to this archive?', 'genesis'), '<code>nofollow</code>' ); ?></label></td>
	</tr>
	
	</table>
	
<?php
}

/**
 * This function executes, via a hook, whenever the user edits
 * a term (category/tag/etc) so that when the term gets saved,
 * its meta information gets saved as well.
 */
add_action('edit_term', 'genesis_term_meta_save', 10, 2);
function genesis_term_meta_save($term_id, $tt_id) {
	
	$term_meta = (array) get_option('genesis-term-meta');
	
	$term_meta[$term_id] = isset( $_POST['meta'] ) ? (array) $_POST['meta'] : array();
	
	update_option('genesis-term-meta', $term_meta);
	
}

/**
 * This function executes, via a hook, whenever the user deletes
 * a term (category/tag/etc) so that when a term is deleted, 
 * its meta info gets deleted as well.
 */
add_action('delete_term', 'genesis_term_meta_delete');
function genesis_term_meta_delete($term_id, $tt_id) {
	
	$term_meta = (array) get_option('genesis-term-meta');
	
	unset( $term_meta[$term_id] );
	
	update_option('genesis-term-meta', (array) $term_meta);
	
}