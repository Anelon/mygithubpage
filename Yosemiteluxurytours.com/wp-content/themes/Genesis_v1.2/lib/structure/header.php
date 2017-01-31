<?php

/**
 * Remove generator, for security
 */
remove_action('wp_head', 'wp_generator');

/**
 * This function handles the doctype
 *
 * @since 1.1
 * @todo build it
 */

/**
 * Show Parent and Child information in the document head if specified by the user.
 * This can be helpful for diagnosing problems with the theme, because you can
 * easily determine if anything is out of date, needs to be updated.
 *
 * @since 1.0
 */
add_action('genesis_meta', 'genesis_show_theme_info_in_head');
function genesis_show_theme_info_in_head() {
	if( !genesis_get_option( 'show_info' ) ) return;
	
	// Show Parent Info
	echo "\n".'<!-- Theme Information -->'."\n";
	echo '<meta name="wp_template" content="'. esc_attr( PARENT_THEME_NAME ) .' '. esc_attr( PARENT_THEME_VERSION ) .'" />'."\n";
	
	// If there is no child theme, don't continue
	if ( CHILD_DIR == PARENT_DIR ) return;
	
	// Show Child Info
	$child_info = get_theme_data(CHILD_DIR.'/style.css');
	echo '<meta name="wp_theme" content="'. esc_attr( $child_info['Name'] ) .' '. esc_attr( $child_info['Version'] ) .'" />'."\n";
}

/**
 * 
 */
add_action('genesis_site_title', 'genesis_seo_site_title');
function genesis_seo_site_title() {
	// Set what goes inside the wrapping tags
	$inside = '<a href="'. trailingslashit( get_bloginfo('url') ) .'" title="'. esc_attr( get_bloginfo('name') ) .'">'.get_bloginfo('name').'</a>';
	
	// Determine which wrapping tags to use
	$wrap = ((genesis_get_seo_option('home_h1_on') == 'title') && (is_home())) ? 'h1' : 'p';

	// Build the Title
	$title = sprintf('<%s id="title">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('genesis_seo_title', $title, $inside, $wrap);
	echo "\n";
}

/**
 * 
 */
add_action('genesis_site_description', 'genesis_seo_site_description');
function genesis_seo_site_description() {
	// Set what goes inside the wrapping tags
	$inside = esc_html ( get_bloginfo( 'description' ) );
	
	// Determine which wrapping tags to use
	$wrap = ((genesis_get_seo_option('home_h1_on') == 'description') && (is_home())) ? 'h1' : 'p';

	// Build the Description
	$description = sprintf('<%s id="description">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('genesis_seo_description', $description, $inside, $wrap);
	echo "\n";
}


/**
 * This function does 3 things:
 * 1. Pulls the values for $sep and $seplocation, uses defaults if necessary
 * 2. Determines if the site title should be appended
 * 3. Allows the user to set a custom title on a per-page/post basis
 *
 * @since 0.1.3
 */
add_action('genesis_title', 'wp_title');
add_filter('wp_title', 'genesis_default_title', 10, 3);
function genesis_default_title($title, $sep, $seplocation) {
	
	if ( is_feed() ) return trim( $title );
	
	$sep = genesis_get_seo_option('doctitle_sep') ? genesis_get_seo_option('doctitle_sep') : '&mdash;';
	$seplocation = genesis_get_seo_option('doctitle_seplocation') ? genesis_get_seo_option('doctitle_seplocation') : 'right';
	
	//	if viewing the homepage
	if ( is_front_page() ) {
		// make $title = site name
		$title = get_bloginfo('name');
		
		// append site description, if necessary
		$title = genesis_get_seo_option('append_description_home') ? $title." $sep ".get_bloginfo('description') : $title;
	}
	
	//	if viewing a post/page/attachment
	if ( is_singular() ) {
		//	The User Defined Title (Genesis)
		if ( genesis_get_custom_field('_genesis_title') ) {
			$title = genesis_get_custom_field('_genesis_title');
		}
		//	All-in-One SEO Pack Title (latest, vestigial)
		elseif ( genesis_get_custom_field('_aioseop_title') ) {
			$title = genesis_get_custom_field('_aioseop_title');
		}
		//	Headspace Title (vestigial)	
		elseif ( genesis_get_custom_field('_headspace_page_title') ) {
			$title = genesis_get_custom_field('_headspace_page_title');
		}
		//	SEO Title Tag (vestigial)
		elseif ( genesis_get_custom_field('title_tag') ) {
			$title = genesis_get_custom_field('title_tag');
		}
		//	All-in-One SEO Pack Title (old, vestigial)
		elseif ( genesis_get_custom_field('title') ) {
			$title = genesis_get_custom_field('title');
		}
	}
	
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		
		$title = !empty( $term->meta['doctitle'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['doctitle'] ) ) : $title;
	}
	
	//	if we don't want site name appended, or if we're on the homepage
	if ( genesis_get_seo_option('append_site_title') == FALSE || is_front_page() ) 
		return esc_html ( trim( $title ) );
	
	// else
	$title = ($seplocation == 'right') ? $title." $sep ".get_bloginfo('name') : get_bloginfo('name')." $sep ".$title;
		return esc_html( trim( $title ) );
}

/**
 * This function generates the <code>META</code> Description based
 * on contextual criteria. Outputs nothing if description isn't there.
 *
 * @since 1.2
 */
add_action('genesis_meta','genesis_seo_meta_description');
function genesis_seo_meta_description() {
	global $post;
	
	$description = '';
	
	// if we're on the homepage
	if ( is_home() ) {
		$description = genesis_get_seo_option('home_description') ? genesis_get_seo_option('home_description') : get_bloginfo('description');
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		// else if description is set via custom field
		if ( genesis_get_custom_field('_genesis_description') ) {
			$description = genesis_get_custom_field('_genesis_description');
		}
		// else if the user used All-in-One SEO Pack (latest, vestigial)
		elseif ( genesis_get_custom_field('_aioseop_description') ) {
			$description = genesis_get_custom_field('_aioseop_description');
		}
		// else if the user used Headspace2 (vestigial)
		elseif ( genesis_get_custom_field('_headspace_description') ) {
			$description = genesis_get_custom_field('_headspace_description');
		}
		// else if the user used All-in-One SEO Pack (old, vestigial)
		elseif ( genesis_get_custom_field('description') ) {
			$description = genesis_get_custom_field('description');
		}
	}
	
	// if we're on a category archive
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		
		$description = !empty( $term->meta['description'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['description'] ) ) : '';
	}
	
	// Add the description, but only if one exists
	if ( !empty($description) ) {
		echo '<meta name="description" content="'.esc_attr( $description ).'" />'."\n";
	}

}

/**
 * This function generates the <code>META</code> Keywords based
 * on contextual criteria. Outputs nothing if keywords aren't there.
 * 
 * @since 1.2
 */
add_action('genesis_meta', 'genesis_seo_meta_keywords');
function genesis_seo_meta_keywords() {
	global $post;
	
	$keywords = '';
	
	// if we're on the homepage
	if( is_home() ) {
		$keywords = genesis_get_seo_option('home_keywords');
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		// if keywords are set via custom field
		if(genesis_get_custom_field('_genesis_keywords')) {
			$keywords = genesis_get_custom_field('_genesis_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (latest, vestigial)
		elseif(genesis_get_custom_field('_aioseop_keywords')) {
			$keywords = genesis_get_custom_field('_aioseop_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (old, vestigial)
		elseif(genesis_get_custom_field('keywords')) {
			$keywords = genesis_get_custom_field('keywords');
		}
		// if all else fails, use the post tags
		else {
			$post_tags = '';
			foreach((array)get_the_tags($post->ID) as $tag) { 
				if($tag) $post_tags .= ','. $tag->name;
			}
			$keywords = substr($post_tags,1); //removing the first "," from the list
		}
	}
	
	// if we're on a category archive
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		
		$keywords = !empty( $term->meta['keywords'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['keywords'] ) ) : '';
	}
	
	// Add the keywords, but only if they exist
	if ( !empty($keywords) ) {
		echo '<meta name="keywords" content="'.esc_attr( $keywords ).'" />'."\n";
	}
	
}

/**
 * This function generates the index/follow code in the document <head>
 *
 * @uses genesis_get_seo_option, genesis_get_custom_field
 *
 * @since 0.1.3
 */
add_action('genesis_meta','genesis_index_follow_logic');
function genesis_index_follow_logic() {
	global $post;
	
	// if the user wants the blog private, then follow logic
	// is unnecessary. WP will insert noindex and nofollow
	if ( get_option('blog_public') == 0 ) return;
	
	// defaults
	$index = 'index'; $follow = 'follow';
	
    // noindex all archives, by default
	if ( is_archive() || is_search() ) {
		$index = 'noindex';
	}
	
	
	// Check SEO Settings to see what archives should be indexed
	if (
		( is_category() && genesis_get_seo_option('index_cat_archive') ) ||
		( is_tag() && genesis_get_seo_option('index_tag_archive') ) ||
		( is_author() && genesis_get_seo_option('index_author_archive') ) ||
		( is_date() && genesis_get_seo_option('index_date_archive') ) ||
		( is_search() && genesis_get_seo_option('index_search_archive') )
	)
	{ $index = 'index'; }
		
	// Check category META to see what archives should be indexed/followed
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$index = $term->meta['noindex'] ? 'noindex' : $index;
		$follow = $term->meta['nofollow'] ? 'nofollow' : $follow;
	}
	
	// Check tag META to see what archives should be indexed/followed
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$index = $term->meta['noindex'] ? 'noindex' : $index;
		$follow = $term->meta['nofollow'] ? 'nofollow' : $follow;
	}
	
	// Check term META to see what archives should be indexed/followed
	if ( is_tax() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		
		$index = $term->meta['noindex'] ? 'noindex' : $index;
		$follow = $term->meta['nofollow'] ? 'nofollow' : $follow;
	}
	
	// noindex pages/posts that are specified to noindex
	if ( is_singular() && genesis_get_custom_field('_genesis_noindex') )
		$index = 'noindex';
	
	// nofollow posts/pages that are specified to nofollow
	if ( is_singular() && genesis_get_custom_field('_genesis_nofollow') )
		$follow = 'nofollow';
	
	echo '<meta name="robots" content="'.esc_attr( $index ).','.esc_attr( $follow ).'" />'."\n";
}

/**
 * This function removes the canonical tag if disabled
 * by the user in the theme settings, or in the in-post SEO settings.
 *
 * @uses genesis_get_seo_option, genesis_get_custom_field
 *
 * @since 0.1.3
 */
add_action('get_header','genesis_canonical');
function genesis_canonical() {
	if ( !genesis_get_seo_option('enable_canonical') || genesis_get_custom_field('_genesis_disable_canonical') )
		remove_action('wp_head', 'rel_canonical');
}

/**
 * This function looks for a favicon. If it finds
 * one, it will output the proper code in the <head>
 *
 * @since 0.2.2
 */
add_action('genesis_meta', 'genesis_load_favicon');
function genesis_load_favicon() {
	
	// Allow child theme to short-circuit this function
	$pre = apply_filters('genesis_pre_load_favicon', false);
	
	if( $pre )
		$favicon = $pre;
	elseif(file_exists(CHILD_DIR.'/images/favicon.ico'))
		$favicon = CHILD_URL.'/images/favicon.ico';
	elseif(file_exists(CHILD_DIR.'/images/favicon.gif'))
		$favicon = CHILD_URL.'/images/favicon.gif';
	elseif(file_exists(CHILD_DIR.'/images/favicon.png'))
		$favicon = CHILD_URL.'/images/favicon.png';
	elseif(file_exists(CHILD_DIR.'/images/favicon.jpg'))
		$favicon = CHILD_URL.'/images/favicon.jpg';
	else
		$favicon = PARENT_URL.'/images/favicon.ico';

	$favicon = apply_filters('genesis_favicon_url', $favicon);

	if($favicon) :
	echo "\n".'<!--The Favicon-->'."\n";
	echo '<link rel="Shortcut Icon" href="'. esc_url( $favicon ). '" type="image/x-icon" />'."\n";
	endif;
}

/**
 * Outputs the structural markup for the header
 *
 * @since 1.2
 */
add_action('genesis_header', 'genesis_header_markup_open', 5);
function genesis_header_markup_open() { ?>
	
	<div id="header">
	<div class="wrap">
	
<?php
}
add_action('genesis_header', 'genesis_header_markup_close', 15);
function genesis_header_markup_close() { ?>
	
	</div><!-- end .wrap -->
	</div><!--end #header-->
	
<?php	
}

/**
 * This function outputs the default header, including the #title-area div,
 * along with #title and #description, as well as the .widget-area.
 *
 * @since 1.0.2
 */
add_action('genesis_header', 'genesis_do_header');
function genesis_do_header() { ?>

		<div id="title-area">
			<?php genesis_site_title(); ?>
			<?php genesis_site_description(); ?>
		</div><!-- end #title-area -->

		<?php if( genesis_get_option('header_right') ) : ?>
		<div class="widget-area">
			<?php dynamic_sidebar('Header Right'); ?>
		</div><!-- end .widget-area -->
		<?php endif; ?>
		
<?php
}

/**
 * Output header scripts in to <code>wp_head()</code>
 * Allow shortcodes
 *
 * @since 0.2.3
 */
add_filter('genesis_header_scripts', 'do_shortcode');
add_action('wp_head', 'genesis_header_scripts');
function genesis_header_scripts() {
	$text = apply_filters('genesis_header_scripts', genesis_get_option('header_scripts'));
	
	echo $text;
}