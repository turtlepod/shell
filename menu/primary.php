<nav <?php hybrid_attr( 'menu', 'primary' ); ?>>

	<div class="menu-container menu-dropdown menu-search">

		<?php tamatebako_menu_toggle( 'primary' ); ?>

		<?php 
		/* Display menu only if the location is registered */
		if ( tamatebako_is_menu_registered( 'primary' ) ){
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container'       => '',
					'menu_id'         => 'menu-primary-items',
					'menu_class'      => 'menu-items',
					'fallback_cb'     => 'tamatebako_menu_fallback_cb',
					'items_wrap'      => '<div class="wrap"><ul id="%s" class="%s">%s</ul></div>'
				)
			);
			
		}
		else{
			tamatebako_menu_fallback_cb();
		}
		?>

		<?php tamatebako_menu_search_form(); ?>

	</div><!-- .menu-container -->

</nav><!-- #menu-primary -->