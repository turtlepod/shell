<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package Shell
 * @subpackage Template
 */
if ( has_nav_menu( 'primary' ) ) : ?>

	<?php do_atomic( 'before_menu_primary' ); // shell_before_menu_primary ?>

	<nav id="menu-primary" class="menu-container">

		<?php do_atomic( 'open_menu_primary' ); // shell_open_menu_primary ?>

		<div class="wrap">

			<?php echo apply_atomic('menu_primary',wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-primary-items', 'fallback_cb' => '', 'echo' => 0 ) )); // shell_menu_primary ?>

		</div>

		<?php do_atomic( 'close_menu_primary' ); // shell_close_menu_primary ?>

	</nav><!-- #menu-primary .menu-container -->

	<?php do_atomic( 'after_menu_primary' ); // shell_after_menu_primary ?>

<?php endif; ?>