<article <?php hybrid_attr( 'post' ); ?>>

	<div class="entry-wrap">

		<header class="entry-header">
			<?php the_title( '<h1 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php tamatebako_attachment(); ?>
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php edit_post_link(); ?>
			<?php tamatebako_entry_terms(); ?>
		</footer><!-- .entry-footer -->

	</div><!-- .entry-wrap -->

</article><!-- .entry -->

<?php if ( is_attachment() ) comments_template( '', true ); // Load comments. ?>