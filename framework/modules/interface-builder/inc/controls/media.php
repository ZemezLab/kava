<?php
/**
 * Class for the building ui-media elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Media' ) ) {

	/**
	 * Class for the building CX_Control_Media elements.
	 */
	class CX_Control_Media extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'                 => 'cx-ui-media-id',
			'name'               => 'cx-ui-media-name',
			'value'              => '',
			'multi_upload'       => true,
			'library_type'       => '', // image, video, sound
			'upload_button_text' => 'Choose Media',
			'label'              => '',
			'class'              => '',
		);

		/**
		 * Register control dependencies
		 *
		 * @return [type] [description]
		 */
		public function register_depends() {
			wp_enqueue_media();
		}

		/**
		 * Retrun scripts dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_script_depends() {
			return array( 'jquery-ui-sortable' );
		}

		/**
		 * Render html CX_Control_Media.
		 *
		 * @since 1.0.0
		 */
		public function render() {

			$html = '';

			if ( ! current_user_can( 'upload_files' ) ) {
				return $html;
			}

			$class = implode( ' ',
				array(
					$this->settings['class'],
				)
			);

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				if ( '' != $this->settings['value'] ) {
					$this->settings['value'] = str_replace( ' ', '', $this->settings['value'] );
					$medias                  = explode( ',', $this->settings['value'] );
				} else {
					$this->settings['value'] = '';
					$medias                  = array();
				}

				$img_style = ! $this->settings['value'] ? 'style="display:none;"' : '' ;

					if ( '' !== $this->settings['label'] ) {
						$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
					}

					$html .= '<div class="cx-ui-media-wrap">';
						$html .= '<div  class="cx-upload-preview" >';
						$html .= '<div class="cx-all-images-wrap">';

							if ( is_array( $medias ) && ! empty( $medias ) ) {

								foreach ( $medias as $medias_key => $medias_value ) {

									$media_title = get_the_title( $medias_value );
									$mime_type   = get_post_mime_type( $medias_value );
									$tmp         = wp_get_attachment_metadata( $medias_value );
									$img_src     = '';
									$thumb       = '';

									switch ( $mime_type ) {
										case 'image/jpeg':
										case 'image/png':
										case 'image/gif':
											$img_src = wp_get_attachment_image_src( $medias_value, 'thumbnail' );
											$img_src = $img_src[0];
											$thumb   = '<img  src="' . esc_html( $img_src ) . '" alt="">';
										break;

										case 'image/x-icon':
											$thumb = '<span class="dashicons dashicons-format-image"></span>';
										break;

										case 'video/mpeg':
										case 'video/mp4':
										case 'video/quicktime':
										case 'video/webm':
										case 'video/ogg':
											$thumb = '<span class="dashicons dashicons-format-video"></span>';
										break;

										case 'audio/mpeg':
										case 'audio/wav':
										case 'audio/ogg':
											$thumb = '<span class="dashicons dashicons-format-audio"></span>';
										break;
									}
									$html .= '<div class="cx-image-wrap">';
										$html .= '<div class="inner">';
											$html .= '<div class="preview-holder" data-id-attr="' . esc_attr( $medias_value ) . '">';
												$html .= '<div class="centered">';
													$html .= $thumb;
												$html .= '</div>';
											$html .= '</div>';
											$html .= '<span class="title">' . $media_title . '</span>';
											$html .= '<a class="cx-remove-image" href="#" title=""><i class="dashicons dashicons-no"></i></a>';
										$html .= '</div>';
									$html .= '</div>';
								}
							}
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="cx-element-wrap">';
						$html .= '<input type="hidden" id="' . esc_attr( $this->settings['id'] ) . '" class="cx-upload-input" name="' . esc_attr( $this->settings['name'] ) . '" value="' . esc_html( $this->settings['value'] ) . '">';
						$html .= '<button type="button" class="upload-button cx-upload-button button-default_" value="' . esc_attr( $this->settings['upload_button_text'] ) . '" data-title="' . esc_attr( $this->settings['upload_button_text'] ) . '" data-multi-upload="' . esc_attr( $this->settings['multi_upload'] ) . '" data-library-type="' . esc_attr( $this->settings['library_type'] ) . '">' . esc_attr( $this->settings['upload_button_text'] ) . '</button>';
						$html .= '<div class="clear"></div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}
	}
}
