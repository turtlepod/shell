<article <?php hybrid_attr( 'post' ); ?>>

	<div class="entry-wrap">

		<div class="entry-header">
			<?php the_title( '<h2 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h2>' ); ?>
		</div><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<div class="entry-footer">
			<?php edit_post_link(); ?>
			<?php tamatebako_entry_terms(); ?>
		</div><!-- .entry-footer -->

	</div><!-- .entry-wrap -->

</article><!-- .entry -->