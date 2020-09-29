<?php

/**
 * Custom WooCommerce breadcrumbs for Cherry
 * (extends default Cherry breadcrumbs)
 */
class Kava_WC_Breadcrumbs extends CX_Breadcrumbs {

	/**
	 * Build breadcrumbs trail items array
	 */
	public function build_trail() {

		$this->is_extend = true;

		// do this for all other pages
		$this->add_network_home_link();
		$this->add_site_home_link();
		$this->add_shop_page();

		if ( is_singular( 'product' ) ) {
			$this->add_single_product();
		} elseif ( is_tax( array( 'product_cat', 'product_tag' ) ) ) {
			$this->add_product_tax();
		}

		/* Add paged items if they exist. */
		$this->add_paged_items();

		/**
		 * Filter final item array
		 *
		 * @var array
		 */
		$this->items = apply_filters( 'cx_breadcrumbs/items', $this->items, $this->args );
	}

	/**
	 * Add single product trail items
	 */
	private function add_single_product() {
		global $post;
		if ( 'minified' !== $this->args['path_type'] ) {
			$terms = false;
			if ( function_exists( 'wc_get_product_terms' ) ) {
				$terms = wc_get_product_terms(
					$post->ID,
					'product_cat',
					array( 'orderby' => 'parent', 'order' => 'DESC' )
				);
			}
			if ( $terms ) {
				$main_term = apply_filters( 'kava-theme/woo/breadcrumbs/main_term', $terms[0], $terms );
				$this->term_ancestors( $main_term->term_id, 'product_cat' );
				$this->_add_item( 'link_format', $main_term->name, get_term_link( $main_term ) );
			}
		}

		$this->_add_item( 'target_format', get_the_title( $post->ID ) );
		$this->page_title = get_the_title( $post->ID );
	}

	/**
	 * Add parent terms items for a term
	 *
	 * @param string $taxonomy
	 */
	private function term_ancestors( $term_id, $taxonomy ) {
		if ( 'minified' === $this->args['path_type'] ) {
			return;
		}
		$ancestors = get_ancestors( $term_id, $taxonomy );
		$ancestors = array_reverse( $ancestors );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, $taxonomy );
			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				$this->_add_item( 'link_format', $ancestor->name, get_term_link( $ancestor ) );
			}
		}
	}

	/**
	 * Get product category page trail link
	 */
	private function add_product_tax() {
		$current_term = $GLOBALS['wp_query']->get_queried_object();
		if ( is_tax( 'product_cat' ) ) {
			$this->term_ancestors( $current_term->term_id, 'product_cat' );
		}
		$this->_add_item( 'target_format', $current_term->name );
	}

	/**
	 * Add WooCommerce shop page
	 */
	private function add_shop_page() {
		$shop_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : false;
		if ( ! $shop_page_id ) {
			return;
		}
		$label = get_the_title( $shop_page_id );
		$url   = get_permalink( $shop_page_id );
		if ( ! is_page( $shop_page_id ) && ! is_post_type_archive( 'product' ) ) {
			$this->_add_item( 'link_format', $label, $url );
		} elseif ( $label ) {
			$this->page_title = $label;
			$this->_add_item( 'target_format', $label );
		}
	}
}
