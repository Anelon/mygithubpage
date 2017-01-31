<?php
/**
 * @todo document this file
 */

/**
 * Output the comments at the end of posts/pages
 *
 * @since 1.1
 */
add_action('genesis_after_post', 'genesis_get_comments_template');
function genesis_get_comments_template() {
	comments_template('', TRUE);
}

add_action('genesis_comments', 'genesis_do_comments');
function genesis_do_comments() { 
	global $post;
	
	if ( is_page() && !genesis_get_option('comments_pages') )
		return;
		
	if ( is_single() && !genesis_get_option('comments_posts') )
		return;
	
	if ( have_comments() ) : ?>
		
	<div id="comments">

		<?php echo apply_filters('genesis_title_comments', __('<h3>Comments</h3>', 'genesis')); ?>

		<ol class="comment-list">
			<?php genesis_list_comments(); ?>
		</ol>
		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
	</div><!--end #comments-->
	
	<?php else : // this is displayed if there are no comments so far ?>
		
	<div id="comments">
		<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<?php echo apply_filters('genesis_no_comments_text', ''); ?>

		<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<?php echo apply_filters('genesis_comments_closed_text', ''); ?>

		<?php endif; // endif comments are open, but there are no comments ?>
	</div><!--end #comments-->
	
	<?php endif; // endif have comments ?>
	
<?php
}

add_action('genesis_pings', 'genesis_do_pings');
function genesis_do_pings() {
	global $post, $wp_query;
	
	if ( is_page() && !genesis_get_option('trackbacks_pages') )
		return;
		
	if ( is_single() && !genesis_get_option('trackbacks_posts') )
		return;

	if ( have_comments() && !empty( $wp_query->comments_by_type['pings'] ) ) : // if have pings ?>
	
	<div id="pings">
		<?php echo apply_filters('genesis_title_pings', __('<h3>Trackbacks</h3>', 'genesis')); ?>

		<ol class="ping-list">
			<?php genesis_list_pings(); ?>
		</ol>
	</div><!-- end #pings -->

	<?php else : // this is displayed if there are no pings ?>

		<?php echo apply_filters('genesis_no_pings_text', ''); ?>

	<?php endif; // endif have pings ?>

<?php	
}

/**
 * This function outputs the comment list to the <code>genesis_comment_list()</code> hook
 *
 * @since 1.0
 */
add_action('genesis_list_comments', 'genesis_default_list_comments');
function genesis_default_list_comments() {
	$args = array(
		'type' => 'comment',
		'avatar_size' => 48,
		'callback' => 'genesis_comment_callback'
	);
	
	$args = apply_filters('genesis_comment_list_args', $args);
	
	wp_list_comments($args);
}

/**
 * This function outputs the pint list to the <code>genesis_ping_list()</code> hook
 * 
 * @since 1.0
 */
add_action('genesis_list_pings', 'genesis_default_list_pings');
function genesis_default_list_pings() {
	$args = array(
		'type' => 'pings'
	);
	
	$args = apply_filters('genesis_ping_list_args', $args);
	
	wp_list_comments($args);
}

/**
 * This function is the comment callback for <code>genesis_default_comment_list()</code>
 * 
 * @since 1.0
 */
function genesis_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	
		<?php genesis_before_comment(); ?>
	
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
			<?php printf( __('<cite class="fn">%s</cite> <span class="says">%s:</span>', 'genesis'), get_comment_author_link(), apply_filters('comment_author_says_text', __('says', 'genesis')) ); ?>
     	</div><!-- end .comment-author -->

		<div class="comment-meta commentmetadata">
			<a href="<?php echo esc_attr( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s', 'genesis'), get_comment_date(),  get_comment_time()); ?></a>
			<?php edit_comment_link(__('Edit', 'genesis'), '&bull; ', ''); ?>
		</div><!-- end .comment-meta -->

		<div class="comment-content">
			<?php if ($comment->comment_approved == '0') : ?>
				<p class="alert"><?php _e('Your comment is awaiting moderation.', 'genesis'); ?></p>
			<?php endif; ?>
			
			<?php comment_text(); ?>
		</div><!-- end .comment-content -->

		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
		
		<?php genesis_after_comment(); ?>
		
	<?php // no ending </li> tag because of comment threading
}

/**
 * This function defines the comment form, hooked to <code>genesis_comments_form()</code>
 *
 * @since 1.0
 */
add_action('genesis_comment_form', 'genesis_do_comment_form');
function genesis_do_comment_form() {
	global $user_identity, $id;
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	
	$args = array(
		'fields' => array(
			'author' =>	'<p class="comment-form-author">' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
						'<label for="author">' . __( 'Name', 'genesis' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-author .form-section -->',
		
			'email' =>	'<p class="comment-form-email">' .
						'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
						'<label for="email">' . __( 'Email', 'genesis' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-email .form-section -->',
		
			'url' =>	'<p class="comment-form-url">' .
						'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
						'<label for="url">' . __( 'Website', 'genesis' ) . '</label>' .
						'</p><!-- #form-section-url .form-section -->'
		),
				
		'comment_field' =>	'<p class="comment-form-comment">' .
							'<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
							'</p><!-- #form-section-comment .form-section -->',
							
		'title_reply' => __( 'Speak Your Mind', 'genesis' ),
		
		'comment_notes_before' => '',
		
		'comment_notes_after' => '',
	);
	
	comment_form( apply_filters('genesis_comment_form_args', $args, $user_identity, $id, $commenter, $req, $aria_req), $id );
}

/**
 * WordPress 3.0 will introduce a new comment form function, but until it is released,
 * we have to create our own. This code is taken directly from WP 3.0 trunk
 * as of March 11, 2010, and will be updated as necessary.
 *
 * When WP 3.0.1 is released, Genesis will no longer support the 2.9 branch,
 * and this code will be removed.
 * 
 * @since 1.1
 */
if( !function_exists('comment_form') ) {
function comment_form( $args = array(), $post_id = null ) {
	global $user_identity, $id;

	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
		            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );
	$defaults = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => __( 'Leave a Reply' ),
		'title_reply_to'       => __( 'Leave a Reply to %s' ),
		'cancel_reply_link'    => __( 'Cancel reply' ),
		'label_submit'         => __( 'Post Comment' ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	?>
		<?php if ( comments_open() ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
			<div id="respond">
				<h3 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php do_action( 'comment_form_top' ); ?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
							<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
							<?php
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							do_action( 'comment_form_after_fields' );
							?>
						<?php endif; ?>
						<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
						<?php echo $args['comment_notes_after']; ?>
						<p class="form-submit">
							<input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
							<?php comment_id_fields(); ?>
						</p>
						<?php do_action( 'comment_form', $post_id ); ?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
<?php
}}