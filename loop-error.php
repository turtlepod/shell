<?php
/**
 * Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package Shell
 * @subpackage Template
 */
?>
	<article id="post-0" class="<?php shell_post_class(); ?>">

		<div class="entry-wrap">

			<div class="entry-content">

				<p><?php _e( 'Apologies, but no entries were found.', 'shell' ); ?></p>

			</div><!-- .entry-content -->

		</div><!-- .entry-wrap -->

	</article><!-- .hentry .error -->