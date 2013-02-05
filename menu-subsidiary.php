<?php
/**
 * Subsidiary Menu Template
 *
 * Displays the Subsidiary Menu if it has active menu items.
 *
 * @package Shell
 * @subpackage Template
 */

if ( has_nav_menu( 'subsidiary' ) ) : ?>

	<?php do_atomic( 'before_menu_subsidiary' ); // shell_before_menu_subsidiary ?>

	<div id="menu-subsidiary" class="menu-container">

		<?php do_atomic( 'open_menu_subsidiary' ); // shell_open_menu_subsidiary ?>

		<div class="wrap">

			<?php echo apply_atomic( 'menu_subsidiary', wp_nav_menu( array( 'theme_location' => 'subsidiary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-subsidiary-items', 'depth' => 1, 'fallback_cb' => '', 'echo' => 0 ) )); // shell_menu_subsidiary ?>

		</div>

		<?php do_atomic( 'close_menu_subsidiary' ); // shell_close_menu_subsidiary ?>

	</div><!-- #menu-subsidiary .menu-container -->

	<?php do_atomic( 'after_menu_subsidiary' ); // shell_after_menu_subsidiary ?>

<?php endif; ?>