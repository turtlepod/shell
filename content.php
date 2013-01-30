<?php
/**
 * Content Template
 *
 * Template used to show post content when a more specific template cannot be found.
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
?>

<?php do_atomic( 'before_entry' ); // shell_before_entry ?>

<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // shell_open_entry ?>

	<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'thumbnail', 'size' => 'thumbnail' ) ); ?>

	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

	<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'shell' ) . '</div>' ); ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
		<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) ); ?>
	</div><!-- .entry-summary -->

	<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="| Tagged "] [entry-comments-link before=" | "]', 'shell' ) . '</div>' ); ?>

	<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

</div><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // shell_after_entry ?>