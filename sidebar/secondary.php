<?php
if ( strpos( get_theme_mod( 'theme_layout' ),'sidebar2' ) === false) {
	return false;
}
?>

<div id="sidebar-secondary-wrap">

	<aside <?php hybrid_attr( 'sidebar', 'secondary' ); ?>>

		<?php if ( is_active_sidebar( 'secondary' ) ) : // If the sidebar has widgets. ?>

			<?php dynamic_sidebar( 'secondary' ); // Displays the secondary sidebar. ?>

		<?php else : // If the sidebar has no widgets. ?>

			<?php the_widget( 'WP_Widget_Categories',
				array(
					'count' => 1,
					'hierarchical' => 1
				),
				array(
					'before_widget' => '<section class="widget widget_categories">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>'
				)
			); ?>

		<?php endif; // End widgets check. ?>

	</aside><!-- #sidebar-secondary -->

</div>