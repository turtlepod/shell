<li <?php hybrid_attr( 'comment' ); ?>>

	<div class="comment-wrap">
		<div class="comment-meta">
			<?php echo get_avatar( $comment ); ?>
			<cite <?php hybrid_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite><br />
			<time <?php hybrid_attr( 'comment-published' ); ?>><?php printf( '%1$s (%2$s)', get_comment_date(), get_comment_time() ) ?></time>
			<a <?php hybrid_attr( 'comment-permalink' ); ?>>#</a>
			<?php edit_comment_link(); ?>
		</div><!-- .comment-meta -->

		<div <?php hybrid_attr( 'comment-content' ); ?>>
			<?php comment_text(); ?>
		</div><!-- .comment-content -->

		<?php hybrid_comment_reply_link(); ?>
	</div><!-- .comment-wrap -->

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>