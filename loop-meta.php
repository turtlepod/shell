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
?>

<?php do_atomic( 'before_loop_meta' ); // shell_before_loop_meta ?>

<div class="loop-meta">

	<?php do_atomic( 'open_loop_meta' ); // shell_open_loop_meta ?>

	<?php echo apply_atomic_shortcode( 'loop_meta_title', '<h1 class="loop-title">[loop-meta-title]</h1>' ); ?>

	<?php echo apply_atomic_shortcode( 'loop_meta_desc', '<div class="loop-description">[loop-meta-desc]</div>' ); ?>

	<?php do_atomic( 'close_loop_meta' ); // shell_close_loop_meta ?>

</div><!-- .loop-meta -->

<?php do_atomic( 'after_loop_meta' ); // shell_after_loop_meta ?>