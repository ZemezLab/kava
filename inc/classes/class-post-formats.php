<?php
/**
 * Post formats class.
 *
 * @package Kava
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Post_Formats' ) ) {

	/**
	 * Define Kava_Post_Formats class
	 */
	class Kava_Post_Formats {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$post_formats = array(
				'image',
				'gallery',
				'video',
				'audio',
				'link',
				'quote',
				'status',
			);

			// Register default post formats
			foreach ( $post_formats as $format ) {
				add_action( 'kava_post_format_' . $format, array( $this, 'post_format_' . $format ) );
			}
		}

		/**
		 * Callback for appropriate hook to show image post format related thumbnail.
		 *
		 * @since  1.0.0
		 * @param  array $args Set of arguments.
		 */
		public function post_format_image( $args = array() ) {
			$post_id = get_the_ID();

			if ( has_post_thumbnail( $post_id ) ) {
				$default_args = array(
					'size' => 'thumbnail',
				);

				$args = wp_parse_args( $args, $default_args );

				$thumb = get_the_post_thumbnail( $post_id, $args['size'] );
				$url   = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );

				echo sprintf(
					'<figure class="post-thumbnail"><a href="%s" class="post-thumbnail__link" data-popup="magnificPopup">%s</a></figure>',
					$url,
					$thumb
				);
			}
		}

		/**
		 * Callback for appropriate hook to show gallery post format related gallery.
		 *
		 * @since  1.0.0
		 * @param  array $args Set of arguments.
		 */
		public function post_format_gallery( $args = array() ) {
			$post_id = get_the_ID();

			$gallery_images = get_post_meta( $post_id, 'kava_extra_gallery_images', true );

			if ( $gallery_images ) {
				$default_args = array(
					'size' => 'thumbnail',
				);

				$args = wp_parse_args( $args, $default_args );

				$gallery_images = explode( ',', $gallery_images );

				$images = '';

				foreach ( $gallery_images as $image ) {
					$thumb = wp_get_attachment_image( $image, $args['size'] );
					$url   = wp_get_attachment_url( $image );

					$images .= sprintf(
						'<figure class="post-thumbnail swiper-slide"><a href="%s" class="post-thumbnail__link" data-popup="magnificPopup">%s</a></figure>',
						$url,
						$thumb
					);
				}

				echo sprintf(
					'<div class="swiper-container">
						<div class="swiper-wrapper">
							%s
						</div>
						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
					</div>',
					$images
				);
			}
		}

		/**
		 * Callback for appropriate hook to show video post format related video.
		 *
		 * @since 1.0.0
		 */
		public function post_format_video() {
			$post_id = get_the_ID();

			$video = null;
			$video_type = get_post_meta( $post_id, 'kava_extra_video_type', true );

			if ( 'library' === $video_type ) {
				$video = wp_get_attachment_url( get_post_meta( $post_id, 'kava_extra_video_library', true ) );
			}

			if ( 'external' === $video_type ) {
				$video = get_post_meta( $post_id, 'kava_extra_video_external', true );
			}

			if ( $video ) {
				$gallery_attr = array(
					'src'      => $video,
					'poster'   => wp_get_attachment_url( get_post_meta( $post_id, 'kava_extra_video_poster', true ) ),
					'loop'     => get_post_meta( $post_id, 'kava_extra_video_loop', true ),
					'autoplay' => get_post_meta( $post_id, 'kava_extra_video_autoplay', true ),
					'preload'  => filter_var( get_post_meta( $post_id, 'kava_extra_video_preload', true ), FILTER_VALIDATE_BOOLEAN ) ? 'auto' : 'none',
					'width'    => get_post_meta( $post_id, 'kava_extra_video_width', true ),
					'height'   => get_post_meta( $post_id, 'kava_extra_video_height', true ),
				);

				echo wp_video_shortcode( $gallery_attr );
			}
		}

		/**
		 * Callback for appropriate hook to show link post format related link.
		 *
		 * @since 1.0.0
		 */
		public function post_format_link() {
			$post_id = get_the_ID();

			$link = get_post_meta( $post_id, 'kava_extra_link', true );

			if ( $link ) {
				$target = get_post_meta( $post_id, 'kava_extra_link_target', true );

				echo sprintf(
					'<div class="post-format-link-wrapper">
						<a href="%1$s" class="post-format-link" target="%2$s">%1$s</a>
					</div>',
					$link,
					$target
				);
			}
		}

		/**
		 * Callback for appropriate hook to show link post format related link.
		 *
		 * @since 1.0.0
		 */
		public function post_format_quote() {
			$post_id = get_the_ID();

			$quote = get_post_meta( $post_id, 'kava_extra_quote_text', true );

			if ( $quote ) {
				$quote_cite = get_post_meta( $post_id, 'kava_extra_quote_cite', true );

				echo sprintf(
					'<blockquote class="post-format-quote">%1$s%2$s</blockquote>',
					$quote,
					$quote_cite ? '<cite>' . $quote_cite . '</cite>' : ''
				);
			}
		}

		/**
		 * Callback for appropriate hook to show audio post format related audio.
		 *
		 * @since  1.0.0
		 * @param  array $args Set of arguments.
		 */
		public function post_format_audio( $args = array() ) {
			$post_id = get_the_ID();
			$audio = get_post_meta( $post_id, 'kava_extra_audio', true );

			if ( $audio ) {
				$attr = array(
					'src'      => wp_get_attachment_url( $audio ),
					'loop'     => get_post_meta( $post_id, 'kava_extra_audio_loop', true ),
					'autoplay' => get_post_meta( $post_id, 'kava_extra_audio_autoplay', true ),
					'preload'  => filter_var( get_post_meta( $post_id, 'kava_extra_audio_preload', true ), FILTER_VALIDATE_BOOLEAN ) ? 'auto' : 'none',
				);

				echo wp_audio_shortcode( $attr );
			}
		}


		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function kava_post_formats() {
	return Kava_Post_Formats::get_instance();
}

kava_post_formats();
