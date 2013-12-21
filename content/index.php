<?php
/**
 * Loop Content Template
 *
 * Template used to show post content when a more specific template cannot be found.
 *
 * @package Shell
 * @subpackage Template
 * @since 0.1.0
 */
do_atomic( 'before_entry' ); // shell_before_entry ?>

<article <?php shell_post_attributes(); ?>>

	<?php
	/* shell_thumbnail() loaded here. only in singular pages. */
	do_atomic( 'open_entry' ); // shell_open_entry ?>

	<div class="entry-wrap">

		<?php do_atomic( 'open_entry_wrap' ); // shell_open_entry_wrap ?>

		<?php
		/**
		 * Singular Pages
		 * ------------------------------------------------
		 */
		if ( is_singular( get_post_type() ) ) { ?>

			<header class="entry-header">

				<?php do_atomic( 'open_entry_header' ); // shell_open_entry_header ?>

				<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

				<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published]', 'shell' ) . '</div>' ); // shell_byline ?>

				<?php do_atomic( 'close_entry_header' ); // shell_close_entry_header ?>

			</header><!-- .entry-header -->

			<?php do_atomic( 'before_entry_content' ); // shell_before_entry_content ?>

			<div class="entry-content">

				<?php do_atomic( 'open_entry_content' ); // shell_open_entry_content ?>

				<?php
				/**
				 * Attachment 
				 * ----------------------------
				 */
				if ( is_attachment() ){

					/* Attachment Image */
					if ( 'image' == get_post_mime_type() ){ ?>

						<p class="attachment-image">
							<?php echo wp_get_attachment_image( get_the_ID(), 'full', false, array( 'class' => 'aligncenter' ) ); ?>
						</p><!-- .attachment-image -->

					<?php } else { /* Non Image Attachment */ ?>

						<?php shell_attachment(); // Function for handling non-image attachments. ?>

						<p class="download">
							<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>" rel="enclosure" type="<?php echo get_post_mime_type(); ?>"><?php printf( __( 'Download &quot;%1$s&quot;', 'shell' ), the_title( '<span class="fn">', '</span>', false) ); ?></a>
						</p><!-- .download -->

					<?php } ?>

				<?php } ?>

				<?php the_content( shell_the_content_more() ); ?>
				<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'shell' ), 'after' => '</p>' ) ); ?>

				<?php do_atomic( 'close_entry_content' ); // shell_close_entry_content ?>

			</div><!-- .entry-content -->

			<?php
			/* shell_attachment_gallery() loaded here on image attachment pages. */
			do_atomic( 'after_entry_content' ); // shell_after_entry_content ?>

			<?php
			/**
			 * Entry Meta
			 * ----------------------------
			 */
			if ( is_singular( 'post' ) ){

				echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms taxonomy="post_tag" before="| Tagged "]', 'shell' ) . '</div>' );

			} else{ /* other post types */

				echo apply_atomic_shortcode( 'entry_meta', '' ); // shell_entry_meta
			
			} ?>

		<?php
		/**
		 * Non Singular Pages
		 * ------------------------------------------------
		 */
		} else { ?>

			<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); // shell_entry_title ?>

			<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( 'By [entry-author] on [entry-published] [entry-edit-link before=" | "]', 'shell' ) . '</div>' ); //shell_byline ?>

			<?php do_atomic( 'before_entry_summary' ); // shell_before_entry_summary ?>

			<div class="entry-summary">

				<?php do_atomic( 'open_entry_summary' ); // shell_open_entry_summary ?>

				<?php the_excerpt(); ?>

				<?php
				/* shell_summary_wp_link_pages() loaded here. */
				do_atomic( 'close_entry_summary' ); // shell_close_entry_summary ?>

			</div><!-- .entry-summary -->

			<?php do_atomic( 'after_entry_summary' ); // shell_after_entry_summary ?>

			<?php
			/**
			 * Entry meta
			 * ----------------------------
			 */
			if ( 'post' == get_post_type( get_the_ID() ) ){

				echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms taxonomy="post_tag" before="| Tagged "]', 'shell' ) . '</div>' );

			} else{ /* other post types */

				echo apply_atomic_shortcode( 'entry_meta', '' ); // shell_entry_meta
			
			} ?>

		<?php } //endif singular/non-singular ?>

		<?php do_atomic( 'close_entry_wrap' ); // shell_close_entry_wrap ?>

	</div><!-- .entry-wrap -->

	<?php do_atomic( 'close_entry' ); // shell_close_entry ?>

</article><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // shell_after_entry ?>