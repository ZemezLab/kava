<?php
/**
 * Search loop template
 */
while ( have_posts() ) : the_post();

	/**
	 * Run the loop for the search to output the results.
	 * If you want to overload this in a child theme then include a file
	 * called content-search.php and that will be used instead.
	 */
	get_template_part( 'template-parts/content', 'search' );

endwhile;

get_template_part( 'template-parts/content', 'navigation' );

if ( have_posts() ) : ?>

	<header class="page-header">
		<h1 class="page-title"><?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search Results for: %s', 'kava' ), '<span>' . get_search_query() . '</span>' );
		?></h1>
	</header><!-- .page-header -->

	<?php

	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', 'search' );

	endwhile;

	get_template_part( 'template-parts/content', 'navigation' );

else :

	get_template_part( 'template-parts/content', 'none' );

endif;