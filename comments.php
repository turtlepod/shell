<?php
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
	return;
?>

<section id="comments-template" class="comments-section">

	<?php if ( have_comments() ) : // Check if there are any comments. ?>

		<div id="comments">

			<div class="comments-header">

				<?php tamatebako_comments_nav(); ?>

				<h3 id="comments-number"><?php comments_number(); ?></h3>

			</div>

			<ol class="comment-list">
				<?php wp_list_comments(
					array(
						'callback'     => 'hybrid_comments_callback',
						'end-callback' => 'hybrid_comments_end_callback'
					)
				); ?>
			</ol><!-- .comment-list -->

		</div><!-- #comments-->

	<?php endif; // End check for comments. ?>

	<?php tamatebako_comments_error(); ?>

	<?php comment_form(); // Loads the comment form. ?>

</section><!-- #comments-template -->