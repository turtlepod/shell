<?php
/**
 * Singular Template
 *
 * This is the default singular template.  It is used when a more specific template can't be found to display
 * singular views of posts (any post type).
 *
 * @package Shell
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // shell_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // shell_open_content ?>

		<div class="hfeed">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // shell_before_entry ?>

					<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

						<?php do_atomic( 'open_entry' ); // shell_open_entry ?>

						<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

						<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'shell' ) . '</div>' ); // shell_byline ?>

						<?php do_atomic( 'before_entry_content' ); // shell_before_entry_content ?>

						<div class="entry-content">

							<?php do_atomic( 'open_entry_content' ); // shell_open_entry_content ?>

							<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'shell' ) ); ?>
							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) ); ?>

							<?php do_atomic( 'close_entry_content' ); // shell_close_entry_content ?>

						</div><!-- .entry-content -->

						<?php do_atomic( 'after_entry_content' ); // shell_after_entry_content ?>

						<?php echo apply_atomic_shortcode( 'entry_meta', '' ); // shell_entry_meta ?>

						<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

					</div><!-- .hentry -->

					<?php do_atomic( 'after_entry' ); // shell_after_entry ?>

					<?php do_atomic( 'after_singular' ); // shell_after_singular ?>

					<?php comments_template( '/comments.php', true ); // Loads the comments.php template. ?>

				<?php endwhile; ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // shell_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // shell_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>