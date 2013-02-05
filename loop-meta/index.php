<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the home page and singular views.
 *
 * @package Shell
 * @subpackage Template
 */
			do_atomic( 'before_loop_meta' ); // shell_before_loop_meta ?>

			<div class="loop-meta">

				<?php do_atomic( 'open_loop_meta' ); // shell_open_loop_meta ?>

				<?php echo shell_loop_meta_title(); // shell_loop_meta_title ?>

				<?php echo shell_loop_meta_description(); // shell_loop_meta_description ?>

				<?php do_atomic( 'close_loop_meta' ); // shell_close_loop_meta ?>

			</div><!-- .loop-meta -->

			<?php do_atomic( 'after_loop_meta' ); // shell_after_loop_meta ?>