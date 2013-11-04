<?php
/**
 * 404 Template
 *
 * The 404 template is used when a reader visits an invalid URL on your site. By default, the template will 
 * display a generic message.
 *
 * @package Shell
 * @subpackage Template
 * @link http://codex.wordpress.org/Creating_an_Error_404_Page
 */

@header( 'HTTP/1.1 404 Not found', true, 404 );

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // shell_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // shell_open_content ?>

		<div class="hfeed">

			<article id="post-0" class="<?php shell_post_class(); ?>">

				<div class="entry-wrap">

					<h1 class="error-404-title entry-title"><?php _e( 'Not Found', 'shell' ); ?></h1>

					<div class="entry-content">

						<p>
						<?php printf( __( 'You tried going to %1$s, and it doesn\'t exist. All is not lost! You can search for what you\'re looking for.', 'shell' ), '<code>' . home_url( esc_url( $_SERVER['REQUEST_URI'] ) ) . '</code>' ); ?>
						</p>

						<?php get_search_form(); // Loads the searchform.php template. ?>

					</div><!-- .entry-content -->

				</div><!-- .entry-wrap -->

			</article><!-- .hentry -->

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // shell_close_content ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // shell_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>