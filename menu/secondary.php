<nav <?php hybrid_attr( 'menu', 'secondary' ); ?>>

	<div class="menu-container menu-dropdown">

		<?php tamatebako_menu_toggle( 'secondary' ); ?>

		<?php 
		/* Display menu only if the location is registered */
		if ( tamatebako_is_menu_registered( 'primary' ) ){
			wp_nav_menu(
				array(
					'theme_location'  => 'secondary',
					'container'       => '',
					'menu_id'         => 'menu-secondary-items',
					'menu_class'      => 'menu-items',
					'fallback_cb'     => '',
					'items_wrap'      => '<div class="wrap"><ul id="%s" class="%s">%s</ul></div>'
				)
			);
		}
		else{
			//tamatebako_menu_fallback_cb();
		}
		?>

	</div><!-- .menu-container -->

</nav><!-- #menu-primary -->