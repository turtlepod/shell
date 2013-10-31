<?php
/**
 * Loop Meta Template for author template 
 *
 * Displays information at the top of the page on author archive views.
 *
 * @package Shell
 * @subpackage Template
 */
			$id = get_query_var( 'author' );
			$author_desc = apply_atomic( 'loop_meta_description',  get_the_author_meta( 'description', $id ) ); // shell_loop_meta_description  ?>

			<header id="hcard-<?php the_author_meta( 'user_nicename', $id ); ?>" class="loop-meta vcard">

				<h1 class="loop-title fn n"><?php echo apply_atomic( 'loop_meta_title', get_the_author_meta( 'display_name', $id ) ); // shell_loop_meta_title ?></h1>

				<div class="loop-description">
					<?php echo apply_atomic( 'loop_meta_avatar', get_avatar( get_the_author_meta( 'user_email', $id ), '100', '', get_the_author_meta( 'display_name', $id ) ) ); // shell_loop_meta_avatar ?>

					<?php if ( !empty( $author_desc ) ) : ?>
					<p class="user-bio">
						<?php echo $author_desc; ?>
					</p><!-- .user-bio -->
					<?php endif; ?>
				</div><!-- .loop-description -->

			</header><!-- .loop-meta -->