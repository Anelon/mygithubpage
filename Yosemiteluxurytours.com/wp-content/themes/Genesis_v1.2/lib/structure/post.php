<?php

/**
 * Post Title
 */
add_action('genesis_post_title', 'genesis_do_post_title');
function genesis_do_post_title() {
	
	if( is_singular() ) {
		$title = '<h1 class="entry-title">'. apply_filters('genesis_post_title_text', get_the_title()) .'</h1>' . "\n";
	}
	
	else {
		$title = '<h2 class="entry-title"><a href="'. get_permalink() .'" title="'. the_title_attribute('echo=0') .'" rel="bookmark">'. apply_filters('genesis_post_title_text', get_the_title()) .'</a></h2>';
	}
	
	echo apply_filters('genesis_post_title_output', $title) . "\n";
	
}

/**
 * Post Image
 */
add_action('genesis_post_content', 'genesis_do_post_image');
function genesis_do_post_image() {
	
	if( !is_singular() && genesis_get_option('content_archive_thumbnail') ) {
		echo '<a href="'.get_permalink().'" title="'.the_title_attribute('echo=0').'">';
		genesis_image(array('format' => 'html', 'size' => genesis_get_option('image_size'), 'attr' => array('class' => 'alignleft post-image')));
		echo '</a>' . "\n";
	}
	
}

/**
 * Post Content
 */
add_action('genesis_post_content', 'genesis_do_post_content');
function genesis_do_post_content() {
	
	if( is_singular() ) {
		the_content(); // display content on posts/pages
		wp_link_pages( array( 'before' => '<p class="pages">' . __( 'Pages:', 'genesis' ), 'after' => '</p>' ) );
		
		if( is_single() ) {
			echo '<!--'; trackback_rdf(); echo '-->' ."\n";
		}
		
		if( is_page() ) {
			edit_post_link(__('(Edit)', 'genesis'), '', '');
		}
		
	}
	elseif( genesis_get_option('content_archive') == 'excerpts' ) {
		the_excerpt();
	}
	else {
		if( genesis_get_option('content_archive_limit') )
			the_content_limit( (int)genesis_get_option('content_archive_limit'), __('[Read more...]', 'genesis') );
		else
			the_content(__('[Read more...]', 'genesis'));
	}
	
}

/**
 * No Posts
 */
add_action('genesis_loop_else', 'genesis_do_noposts');
function genesis_do_noposts() {
	
	echo '<p>'. apply_filters('genesis_noposts_text', __('Sorry, no posts matched your criteria.', 'genesis')) .'</p>' . "\n";
	
}

/**
 * Add the post info (byline) under the title
 *
 * @since 0.2.3
 */
add_filter('genesis_post_info', 'do_shortcode', 20);
add_action('genesis_before_post_content', 'genesis_post_info');
function genesis_post_info() {
	if(is_page()) return; // don't do post-info on pages
	
	echo '<div class="post-info">'."\n\t\t\t\t";
	
	$post_info = '[post_date] ' . __('By', 'genesis') . ' [post_author_posts_link] [post_comments] [post_edit]';
	echo apply_filters('genesis_post_info', $post_info);
		
	echo "\n\t\t\t\t".'</div>'."\n\n";
}

/**
 * Add the post meta after the post content 
 *
 * @since 0.2.3
 */
add_filter('genesis_post_meta', 'do_shortcode', 20);
add_action('genesis_after_post_content', 'genesis_post_meta');
function genesis_post_meta() {
	if(is_page()) return; // don't do post-meta on pages
	
	echo '<div class="post-meta">'."\n\t\t\t\t";
	
	$post_meta = '[post_categories] [post_tags]';
	echo apply_filters('genesis_post_meta', $post_meta);
	//	genesis_post_categories_link(', ', __('Filed Under', 'genesis'));
	//	genesis_post_tags_link(', ', __('Tagged With', 'genesis'));
	
	echo "\n\t\t\t\t".'</div>'."\n\n";
}

/**
 * Add the author info box after a single post
 * 
 * @since 1.0
 */
add_action('genesis_after_post', 'genesis_do_author_box');
function genesis_do_author_box() { 
	if( is_single() && genesis_get_option( 'author_box' ) ) :
?>
	<div class="author-box">
		<p><?php echo get_avatar( get_the_author_meta('email'), apply_filters('genesis_author_box_gravatar_size', '70') ); ?><b><?php _e('About', 'genesis'); ?> <?php the_author(); ?></b><br /><?php the_author_meta( 'description' ); ?></p>
	</div><!-- end .authorbox -->
<?php
	endif;
}

/**
 * The Genesis-specific post date
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_date($format = '', $label = '') {
	// pull the $format, or use the default
	$format = (!empty($format)) ? $format : get_option('date_format');
	
	$label = (!empty($label)) ? trim($label).' ' : '';

	echo sprintf('<span class="time published" title="%3$s">%1$s%2$s</span> ', $label, get_the_time($format), get_the_time('Y-m-d\TH:i:sO'));
}

/**
 * The Genesis-specific post author link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_author_posts_link($label = '') {
	global $authordata;
	$id = $authordata->ID;
	$nicename = $authordata->user_nicename;
	$display_name = get_the_author();
	
	$url = get_author_posts_url($id, $nicename);
	$title = sprintf(__('Posts by %s', 'genesis'), $display_name);
	
	$link = sprintf( '<a href="%1$s" title="%2$s" class="url fn">%3$s</a>', esc_url( $url ), esc_attr( $title ), esc_html( $display_name ) );
	
	// nofollow the link, if necessary
	$link = (genesis_get_seo_option('nofollow_author_link')) ? genesis_rel_nofollow($link) : $link;
	
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	echo sprintf('<span class="author vcard">%1$s%2$s</span> ', esc_html( $label ), $link);
}

/**
 * The Genesis-specific post comments link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_comments_link($zero = false, $one = false, $more = false) {
	global $post, $id;
	
	if ( 0 == genesis_get_option('comments_posts') or 'closed' == $post->comment_status )
	        return;

	$number = get_comments_number($id);

	if ( $number > 1 )
		$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', 'genesis') : $more);
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __('No Comments', 'genesis') : $zero;
	else // must be one
		$output = ( false === $one ) ? __('1 Comment', 'genesis') : $one;
	
	$link = sprintf('<a href="%s">%s</a>', get_permalink().'#respond', $output);
	
	// nofollow the link, if necessary
	$link = genesis_get_seo_option('nofollow_comments_link') ? genesis_rel_nofollow($link) : $link;
	
	echo sprintf('<span class="post-comments">%s</span> ', $link);
}

/**
 * The Genesis-specific post categories link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_categories_link($sep = ', ', $label = '') {
	$links = get_the_category_list($sep);
	$links = (genesis_get_seo_option('nofollow_cat_link')) ? genesis_rel_nofollow($links) : $links;
	
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	echo sprintf('<span class="categories">%1$s%2$s</span> ', $label, $links);
}

/**
 * The Genesis-specific post tags link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_tags_link($sep = ', ', $label = '') {
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	$links = get_the_tag_list($label, $sep);
	$links = (genesis_get_seo_option('nofollow_tag_link')) ? genesis_rel_nofollow($links) : $links;
	
	echo sprintf('<span class="tags">%s</span> ', $links);
}

/**
 * The default post navigation, hooked to genesis_after_endwhile
 *
 * @since 0.2.3
 */
add_action('genesis_after_endwhile', 'genesis_posts_nav');
function genesis_posts_nav() {
	$nav = genesis_get_option('posts_nav');
	
	if($nav == 'prev-next')
		genesis_prev_next_posts_nav();
	elseif($nav == 'numeric')
		genesis_numeric_posts_nav();
	else
		genesis_older_newer_posts_nav();
}

/**
 * Display older/newer posts navigation
 * 
 * @since 0.2.2
 */
function genesis_older_newer_posts_nav() {
	
	$older = get_next_posts_link('&laquo; ' . __('Older Posts', 'genesis'));
	$newer = get_previous_posts_link(__('Newer Posts', 'genesis') . ' &raquo;');
	
	$nav = '
	<div class="navigation">
		<div class="alignleft">'.$older.'</div>
		<div class="alignright">'.$newer.'</div>
	</div><!-- end .navigation -->
	';
	
	if(!empty($older) || !empty($newer))
		echo $nav;
}

/**
 * Display prev/next posts navigation
 * 
 * @since 0.2.2
 */
function genesis_prev_next_posts_nav() {
	
	$prev = get_previous_posts_link();
	$next = get_next_posts_link();
	
	$nav = '
	<div class="navigation">
		<div class="alignleft">'.$prev.'</div>
		<div class="alignright">'.$next.'</div>
	</div><!-- end .navigation -->
	';
	
	if(!empty($prev) || !empty($next))
		echo $nav;
}

/**
 * Display numeric posts navigation (similar to WP-PageNavi)
 *
 * @since 0.2.3
 */
function genesis_numeric_posts_nav() {
	if(is_singular()) return; // do nothing
	
	global $wp_query;
	
	// Stop execution if there's only 1 page
	if( $wp_query->max_num_pages <= 1 ) return;
	
	$paged = get_query_var('paged') ? absint( get_query_var('paged') ) : 1;
	$max = intval($wp_query->max_num_pages);
	$newline = "\n";
	
	echo '<div class="navigation">'.$newline;
	echo '<ul>'.$newline;
		
	//	add current page to the array
	if($paged >= 1)
		$links[] = $paged;
	
	//	add the pages around the current page to the array
	if($paged >= 3) {
		$links[] = $paged - 1; $links[] = $paged - 2;
	}
	if(($paged + 2) <= $max) { 
		$links[] = $paged + 2; $links[] = $paged + 1;
	}
	
	//	Previous Post Link
	if(get_previous_posts_link())
		echo '<li>'.get_previous_posts_link(__('&laquo; Previous', 'genesis')).'</li>'.$newline;
	
	//	Link to first Page, plus ellipeses, if necessary
	if(!in_array(1, $links)) {
		if($paged == 1) $current = ' class="active"'; else $current = null;
		echo '<li'.$current.'><a href="'.get_pagenum_link(1).'">1</a></li>'.$newline;
		
		if(!in_array(2, $links))
		echo '<li>&hellip;</li>'.$newline;
	}
	
	//	Link to Current page, plus 2 pages in either direction (if necessary).
	sort($links);
	foreach($links as $link) {
		if($paged == $link) $current = ' class="active"'; else $current = null;
		echo '<li'.$current.'><a href="'.get_pagenum_link($link).'">'.$link.'</a></li>'.$newline;
	}
	
	//	Link to last Page, plus ellipses, if necessary
	if(!in_array($max, $links)) {
		if(!in_array($max - 1, $links))
		echo '<li>&hellip;</li>'.$newline;
		
		if($paged == $max) $current = ' class="active"'; else $current = null;
		echo '<li'.$current.'><a href="'.get_pagenum_link($max).'">'.$max.'</a></li>'.$newline;
	}
	
	//	Next Post Link
	if(get_next_posts_link())
		echo '<li>'.get_next_posts_link(__('Next &raquo;', 'genesis')).'</li>'.$newline;
	
	echo '</ul>'.$newline;
	echo '</div>'.$newline;
}