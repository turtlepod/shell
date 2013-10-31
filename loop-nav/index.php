<?php
/**
 * Loop Nav Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Shell
 * @subpackage Template
 */
?>

	<?php if ( current_theme_supports( 'loop-pagination' ) ) : loop_pagination(); ?>

	<?php else : $nav = get_posts_nav_link( array( 'sep' => '', 'prelabel' => '<span class="previous">' . __( '&larr; Previous', 'shell' ) . '</span>', 'nxtlabel' => '<span class="next">' . __( 'Next &rarr;', 'shell' ) . '</span>' ) ) ?>

		<nav class="loop-nav">
			<?php echo $nav; ?>
		</nav><!-- .loop-nav -->

	<?php endif; ?>