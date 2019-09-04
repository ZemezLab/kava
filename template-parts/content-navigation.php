<?php
/**
 * Template part for posts navigation.
 *
 * @package Kava
 */

do_action( 'kava-theme/blog/posts-navigation-before' );

echo '<div class="posts-list-navigation">';

$navigation_type = kava_theme()->customizer->get_value( 'blog_navigation_type' );

switch ( $navigation_type ) {
	case 'navigation':
		the_posts_navigation(
			apply_filters( 'kava-theme/posts/navigation-args',
							array(
								'prev_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									<i class="fa fa-angle-left" aria-hidden="true"></i> %1$s',
									esc_html__( 'Older Posts', 'kava' ) ),
								'next_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									%1$s <i class="fa fa-angle-right" aria-hidden="true"></i>',
									esc_html__( 'Newer Posts', 'kava' ) ),
							)
			)
		);
		break;
	case 'pagination':
		the_posts_pagination( 
			apply_filters( 'kava-theme/posts/pagination-args',
							array(
								'prev_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									<i class="fa fa-angle-left" aria-hidden="true"></i> %1$s',
									esc_html__( 'Prev', 'kava' ) ),
								'next_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									%1$s <i class="fa fa-angle-right" aria-hidden="true"></i>',
									esc_html__( 'Next', 'kava' ) ),
							)
			)
		);
		break;
}

echo '</div>';

do_action( 'kava-theme/blog/posts-navigation-after' );
