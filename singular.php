<?php
/**
 * Singular Template
 *
 * This is the default singular template.  It is used when a more specific template can't be found to display
 * singular views of posts (any post type).
 *
 * @package Shell
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // shell_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // shell_open_content ?>

		<div class="hfeed">

			<?php do_atomic( 'open_hfeed' ); // shell_open_hfeed ?>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php shell_get_atomic_template( 'content', true ); // atomic context loop template, in "content" directory ?>

					<?php do_atomic( 'after_singular' ); // shell_after_singular ?>

					<?php comments_template( '/comments.php', true ); // Loads the comments.php template. ?>

				<?php endwhile; ?>

			<?php endif; ?>

			<?php do_atomic( 'close_hfeed' ); // shell_close_hfeed ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // shell_close_content ?>

		<?php shell_get_atomic_template( 'loop-nav' ); // atomic context loop template, in "loop-nav" directory ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // shell_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>