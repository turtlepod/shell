<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package Shell
 * @subpackage Template
 */
?>
				<?php do_atomic( 'sidebar' ); // shell_sidebar ?>

				<?php do_atomic( 'close_main' ); // shell_close_main ?>

			</div><!-- .wrap -->

		</div><!-- #main -->

		<?php do_atomic( 'after_main' ); // shell_after_main ?>

		<?php do_atomic( 'before_footer' ); // shell_before_footer ?>

		<div id="footer">

			<?php do_atomic( 'open_footer' ); // shell_open_footer ?>

			<div class="wrap">

				<?php echo apply_atomic_shortcode( 'footer_content', shell_footer_content() ); // shell_footer_content ?>

				<?php do_atomic( 'footer' ); // shell_footer ?>

			</div><!-- .wrap -->

			<?php do_atomic( 'close_footer' ); // shell_close_footer ?>

		</div><!-- #footer -->

		<?php do_atomic( 'after_footer' ); // shell_after_footer ?>

	</div><!-- #container -->

	<?php do_atomic( 'close_body' ); // shell_close_body ?>

	<?php wp_footer(); // wp_footer ?>

</body>
</html>