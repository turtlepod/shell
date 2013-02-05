<?php
/**
 * Loop Content Template for Singular Attachment (post type) template
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
?>

<?php do_atomic( 'before_entry' ); // shell_before_entry ?>

<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // shell_open_entry ?>

	<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

	<?php do_atomic( 'before_entry_content' ); // shell_before_entry_content ?>

	<div class="entry-content">

		<?php do_atomic( 'open_entry_content' ); // shell_open_entry_content ?>

		<?php hybrid_attachment(); // Function for handling non-image attachments. ?>

		<p class="download">
			<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>" rel="enclosure" type="<?php echo get_post_mime_type(); ?>"><?php printf( __( 'Download &quot;%1$s&quot;', 'shell' ), the_title( '<span class="fn">', '</span>', false) ); ?></a>
		</p><!-- .download -->

		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'shell' ) ); ?>

		<?php do_atomic( 'close_entry_content' ); // shell_close_entry_content ?>

	</div><!-- .entry-content -->

	<?php do_atomic( 'after_entry_content' ); // shell_after_entry_content ?>

	<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

</div><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // shell_after_entry ?>
