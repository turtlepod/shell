<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the home page and singular views.
 *
 * @package Shell
 * @subpackage Template
 */
	do_atomic( 'before_loop_meta' ); // shell_before_loop_meta  ?>

	<?php
	/**
	 * In Author Archive Pages
	 * ---------------------------------------
	 */
	if ( is_author() ){

		$id = get_query_var( 'author' );
		$author_desc = apply_atomic( 'loop_meta_description',  get_the_author_meta( 'description', $id ) ); // shell_loop_meta_description  ?>

		<header id="hcard-<?php the_author_meta( 'user_nicename', $id ); ?>" class="loop-meta vcard">

			<?php do_atomic( 'open_loop_meta' ); // shell_open_loop_meta ?>

			<h1 class="loop-title fn n"><?php echo apply_atomic( 'loop_meta_title', get_the_author_meta( 'display_name', $id ) ); // shell_loop_meta_title ?></h1>

			<div class="loop-description">
				<?php echo apply_atomic( 'loop_meta_avatar', get_avatar( get_the_author_meta( 'user_email', $id ), '100', '', get_the_author_meta( 'display_name', $id ) ) ); // shell_loop_meta_avatar ?>

				<?php if ( !empty( $author_desc ) ) : ?>
				<p class="user-bio">
					<?php echo $author_desc; ?>
				</p><!-- .user-bio -->
				<?php endif; ?>
			</div><!-- .loop-description -->

		<?php do_atomic( 'close_loop_meta' ); // shell_close_loop_meta ?>

		</header><!-- .loop-meta -->

	<?php
	/**
	 * Other Archive Type Pages
	 * ---------------------------------------
	 */
	} elseif ( !is_front_page()  ) { ?>

		<header class="loop-meta">

			<?php do_atomic( 'open_loop_meta' ); // shell_open_loop_meta ?>

			<?php echo shell_loop_meta_title(); // shell_loop_meta_title ?>

			<?php echo shell_loop_meta_description(); // shell_loop_meta_description ?>

			<?php do_atomic( 'close_loop_meta' ); // shell_close_loop_meta ?>

		</header><!-- .loop-meta -->

	<?php } /* endif */ ?>

	<?php do_atomic( 'after_loop_meta' ); // shell_after_loop_meta ?>