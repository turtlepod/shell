<?php
/**
 * Secondary Menu Template
 *
 * Displays the Secondary Menu if it has active menu items.
 *
 * @package Shell
 * @subpackage Template
 */

if ( has_nav_menu( 'secondary' ) ) : ?>


	<?php do_atomic( 'before_menu_secondary' ); // shell_before_menu_secondary ?>

	<div id="menu-secondary" class="menu-container">

		<div id="menu-icon" class="mobile-button"></div>

			<?php do_atomic( 'open_menu_secondary' ); // shell_open_menu_secondary ?>

			<div class="wrap">


				<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-secondary-items', 'fallback_cb' => '' ) ); ?>


			</div>
			
			<?php do_atomic( 'close_menu_secondary' ); // shell_close_menu_secondary ?>

	</div><!-- #menu-secondary .menu-container -->

	<?php do_atomic( 'after_menu_secondary' ); // shell_after_menu_secondary ?>

<?php endif; ?>