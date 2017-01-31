<?php get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div id="content-sidebar-wrap">

	<?php genesis_before_content(); ?>
	<div id="content">
	
	<?php genesis_before_loop(); ?>
		
		<?php genesis_before_post(); ?>
		<div class="post">

			<h1><?php _e('Not Found, Error 404', 'genesis'); ?></h1>
			<p><?php printf(__('The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it with the information below.', 'genesis'), get_bloginfo('url')); ?></p>

			<div class="archive-page">

				<h4><?php _e("Pages:", 'genesis'); ?></h4>
				<ul>
					<?php wp_list_pages('title_li='); ?>
				</ul>

				<h4><?php _e("Categories:", 'genesis'); ?></h4>
				<ul>
					<?php wp_list_categories('sort_column=name&title_li='); ?>
				</ul>

			</div><!-- end .archive-page-->

			<div class="archive-page">

				<h4><?php _e("Authors:", 'genesis'); ?></h4>
				<ul>
					<?php wp_list_authors('exclude_admin=0&optioncount=1'); ?>   
				</ul>

				<h4><?php _e("Monthly:", 'genesis'); ?></h4>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>

				<h4><?php _e("Recent Posts:", 'genesis'); ?></h4>
				<ul>
					<?php wp_get_archives('type=postbypost&limit=100'); ?> 
				</ul>    

			</div><!-- end .archive-page-->
								
		</div><!-- end .postclass -->
		<?php genesis_after_post(); ?>

	<?php genesis_after_loop(); ?>
	
	</div><!-- end #content -->
	<?php genesis_after_content(); ?>

</div><!-- end #content-sidebar-wrap -->
<?php genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>