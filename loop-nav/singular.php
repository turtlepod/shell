<?php
/**
 * Loop Nav Template For Singular Pages
 *
 * @package Shell
 * @subpackage Template
 */
?>
	<?php if ( is_singular( 'post' ) ) : ?>
		<nav class="loop-nav">
			<?php previous_post_link( '<div class="previous">' . __( 'Previous Entry: %link', 'shell' ) . '</div>', '%title' ); ?>
			<?php next_post_link( '<div class="next">' . __( 'Next Entry: %link', 'shell' ) . '</div>', '%title' ); ?>
		</nav><!-- .loop-nav -->
	<?php endif; ?>