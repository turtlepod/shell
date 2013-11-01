<?php
/**
 * Loop Content Template for Page Post Type in Search Result Page
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
?>

<?php do_atomic( 'before_entry' ); // shell_before_entry ?>

<article <?php hybrid_post_attributes(); ?>>

	<?php do_atomic( 'open_entry' ); // shell_open_entry ?>

	<div class="entry-wrap">

		<?php do_atomic( 'open_entry_wrap' ); // shell_open_entry_wrap ?>

		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

		<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'shell' ) . '</div>' ); //shell_byline ?>

		<?php do_atomic( 'before_entry_summary' ); // shell_before_entry_summary ?>

		<div class="entry-summary">

			<?php do_atomic( 'open_entry_summary' ); // shell_open_entry_summary ?>

			<?php the_excerpt(); ?>

			<?php do_atomic( 'close_entry_summary' ); // shell_close_entry_summary ?>

		</div><!-- .entry-summary -->

		<?php do_atomic( 'after_entry_summary' ); // shell_after_entry_summary ?>

		<?php do_atomic( 'close_entry_wrap' ); // shell_close_entry_wrap ?>

	</div><!-- .entry-wrap -->

	<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

</article><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // shell_after_entry ?>