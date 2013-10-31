<?php
/**
 * Mobile Menu Bottom
 *
 * Displays the Primary and Secondary Menu in the footer if it has active menu items.
 * Only loaded if wp_is_mobile is true and browser width < 480px.
 * This is removed by javascript when javascript is enable in browser.
 *
 * @package Shell
 * @subpackage Template
 */
if ( has_nav_menu( 'primary' ) ) {

	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'=> 'nav',
			'container_id'=> 'menu-primary-bottom',
			'container_class' => 'menu-bottom-container',
			'menu_id' => 'menu-primary-bottom-items',
			'menu_class' => 'menu-bottom-items',
			'fallback_cb' => '',
			'echo' => 1,
			'items_wrap' => '<div class="menu-bottom-title">' . _x( 'Navigation', 'Menu bottom title', 'shell' ) . ' <a href="#container">' . __( 'Back to top', 'shell' ) . '</a></div></strong><ul id="%1$s" class="%2$s">%3$s</ul>'
		)
	);

}
if ( has_nav_menu( 'secondary' ) ) {

	wp_nav_menu(
		array(
			'theme_location' => 'secondary',
			'container'=> 'nav',
			'container_id'=> 'menu-secondary-bottom',
			'container_class' => 'menu-bottom-container',
			'menu_id' => 'menu-secondary-bottom-items',
			'menu_class' => 'menu-bottom-items',
			'fallback_cb' => '',
			'echo' => 1,
			'items_wrap' => '<div class="menu-bottom-title">' . _x( 'Navigation', 'Menu bottom title', 'shell' ) . ' <a href="#container">' . __( 'Back to top', 'shell' ) . '</a></div></strong><ul id="%1$s" class="%2$s">%3$s</ul>'
		)
	);

} ?>