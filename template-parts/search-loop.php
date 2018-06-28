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
