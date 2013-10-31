<?php
/**
 * Loop Meta Template for home template 
 *
 * Displays information at the top of the page on blog page views.
 *
 * @package Shell
 * @subpackage Template
 */
	if ( !is_front_page() ) : // do not add in front page.

			do_atomic( 'before_loop_meta' ); // shell_before_loop_meta ?>

			<header class="loop-meta">

				<?php do_atomic( 'open_loop_meta' ); // shell_open_loop_meta ?>

				<?php echo shell_loop_meta_title(); // shell_loop_meta_title ?>

				<?php echo shell_loop_meta_description(); // shell_loop_meta_description ?>

				<?php do_atomic( 'close_loop_meta' ); // shell_close_loop_meta ?>

			</header><!-- .loop-meta -->

			<?php do_atomic( 'after_loop_meta' ); // shell_after_loop_meta ?>

	<?php endif; ?>