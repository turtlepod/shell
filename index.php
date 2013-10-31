<?php
/**
 * Home Template
 *
 * This is the home template.  Technically, it is the "posts page" template.  It is used when a visitor is on the 
 * page assigned to show a site's latest blog posts.
 *
 * @package Shell
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // shell_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // shell_open_content ?>

		<div class="hfeed">

			<?php do_atomic( 'open_hfeed' ); // shell_open_hfeed ?>

			<?php
			/**
			 * In non singular template load 'loop-meta' content
			 * this will load template files in 'loop-meta' directory based on current page context.
			 */
			if (!is_singular() ){

				shell_get_atomic_template( 'loop-meta' ); // atomic context template, in "loop-meta" directory

			}
			?>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php shell_get_atomic_template( 'content', true ); // atomic context loop template, in "content" directory ?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

			<?php do_atomic( 'close_hfeed' ); // shell_close_hfeed ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // shell_close_content ?>

		<?php shell_get_atomic_template( 'loop-nav' ); // atomic context loop template, in "loop-nav" directory ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // shell_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>