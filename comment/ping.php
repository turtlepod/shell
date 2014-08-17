<li <?php hybrid_attr( 'comment' ); ?>>

	<div class="comment-meta">
		<cite <?php hybrid_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite><br />
		<time <?php hybrid_attr( 'comment-published' ); ?>><?php printf( '%1$s / %2$s', get_comment_date(), get_comment_time() ) ?></time>
		<a <?php hybrid_attr( 'comment-permalink' ); ?>>#</a>
		<?php edit_comment_link(); ?>
	</div><!-- .comment-meta -->

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>