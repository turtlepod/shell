<?php
/**
 * Subsidiary Sidebar Template
 *
 * Displays widgets for the Subsidiary dynamic sidebar if any have been added to the sidebar through the 
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package Shell
 * @subpackage Template
 */

if ( is_active_sidebar( 'subsidiary' ) ) : ?>

	<?php do_atomic( 'before_sidebar_subsidiary' ); // shell_before_sidebar_subsidiary ?>

	<div id="sidebar-subsidiary" class="sidebar">

		<div class="wrap">

			<?php do_atomic( 'open_sidebar_subsidiary' ); // shell_open_sidebar_subsidiary ?>

			<?php dynamic_sidebar( 'subsidiary' ); ?>

			<?php do_atomic( 'close_sidebar_subsidiary' ); // shell_close_sidebar_subsidiary ?>

		</div><!-- #sidebar-subsidiary .wrap -->

	</div><!-- #sidebar-subsidiary -->

	<?php do_atomic( 'after_sidebar_subsidiary' ); // shell_after_sidebar_subsidiary ?>

<?php endif; ?>