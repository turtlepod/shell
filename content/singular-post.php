<?php
/**
 * Loop Content Template for Singular Post (post type) template
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
?>

<?php do_atomic( 'before_entry' ); // shell_before_entry ?>

<article <?php hybrid_post_attributes(); ?>>

	<?php do_atomic( 'open_entry' ); // shell_open_entry ?>

	<header="entry-header">

		<?php do_atomic( 'open_entry_header' ); // shell_open_entry_header ?>

		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

		<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published]', 'shell' ) . '</div>' ); // shell_byline ?>

		<?php do_atomic( 'close_entry_header' ); // shell_close_entry_header ?>

	</header><!-- .entry-header -->

	<?php do_atomic( 'before_entry_content' ); // shell_before_entry_content ?>

	<div class="entry-content">

		<?php do_atomic( 'open_entry_content' ); // shell_open_entry_content ?>

		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'shell' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) ); ?>

		<?php do_atomic( 'close_entry_content' ); // shell_close_entry_content ?>

	</div><!-- .entry-content -->

	<?php do_atomic( 'after_entry_content' ); // shell_after_entry_content ?>

	<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms taxonomy="post_tag" before="| Tagged "]', 'shell' ) . '</div>' ); ?>

	<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

</article><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // shell_after_entry ?>
