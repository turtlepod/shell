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
	<article <?php hybrid_post_attributes(); ?>>

		<div class="entry-content">

			<p><?php _e( 'Apologies, but no entries were found.', 'shell' ); ?></p>

		</div><!-- .entry-content -->

	</article><!-- .hentry .error -->