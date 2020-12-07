<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_Tyto_AdditionalFields extends Widget_Base {


	public function get_name() {
		return 'Additional Fields';
	}

	public function get_title() {
		return __( 'Additional Fields' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'tyto' ];
	}

	public function render() {
		$settings                 = $this->get_settings_for_display();
		$current_additional_field = $settings['current_additional_field'];
		$response                 = \TyTo\Api::getInstance()->get( '/api/additionalfields' );
		$additionalFields_        = [];
		if ( ! empty( $response['records'] ) ) {
			foreach ( $response['records'] as $r ) {
				if ( $r['name'] !== 'servicesIncluded' && $r['name'] !== 'servicesExcluded' ) {
					$additionalFields_[ $r['name'] ] = $r['fieldLabel'];
				}
			}
		}
		set_query_var( 'additional_fields', $additionalFields_ );
		$values_front = json_decode( get_post_meta( get_the_id(), 'tytorawdata', true ) );

		$curr_additional_field_front = [];
		foreach ( $values_front as $k => $v ) {
			if ( $k == 'additionalFields' ) {
				if ( $v !== null ) {
					foreach ( $v as $_k => $_v ) {
						if ( $_v !== null && $_v !== '' ) {
							$curr_additional_field_front[ $_k ] = $_v;
						}
					}
				}
			}
		}

		$render_array = [];

		foreach ( $curr_additional_field_front as $_key => $_value ) {
			if ( $_key == $current_additional_field ) {
				$render_array['title'] = $additionalFields_[ $_key ];
				$render_array['desc']  = $_value;
			}
		}
		if ( $settings['section_layout'] == 'template_two' ) {


			?>
            <div id="<?php echo get_the_ID(); ?>" class="tour-section">
                <h5 class="tour-section-title"><?php echo $render_array['title']; ?></h5>
                <div>
					<?php echo $render_array['desc']; ?>
                </div>
            </div>
			<?php
		} else {
			?>
            <div id="<?php echo get_the_ID(); ?>" class="tour-section" style='text-align:right;'>
                <h5 class="tour-section-title"><?php echo $render_array['title']; ?></h5>
                <div>
					<?php echo $render_array['desc']; ?>
                </div>
            </div>
			<?php
		}
	}

	public function render_plain_content() {
	}

	protected function _register_controls() {
		$this->start_controls_section( 'l_layout', array(
			'label' => esc_html__( 'Options' ),
		) );
		$response          = \TyTo\Api::getInstance()->get( '/api/additionalfields' );
		$additionalFields_ = [];
		if ( ! empty( $response['records'] ) ) {
			foreach ( $response['records'] as $r ) {
				if ( $r['name'] !== 'servicesIncluded' && $r['name'] !== 'servicesExcluded' ) {
					$additionalFields_[ $r['name'] ] = $r['fieldLabel'];
				}
			}
		}
		set_query_var( 'additional_fields', $additionalFields_ );
		$values                = json_decode( get_post_meta( get_the_id(), 'tytorawdata', true ) );
		$curr_additional_field = [];
		foreach ( $values as $k => $v ) {
			if ( $k == 'additionalFields' ) {
				if ( $v !== null ) {
					foreach ( $v as $_k => $_v ) {
						if ( $_v !== null && $_v !== '' ) {
							$curr_additional_field[ $_k ] = $additionalFields_[ $_k ];
						}
					}
				}
			}
		}

		$this->add_control(
			'current_additional_field',
			[
				'label'   => __( 'Choose additional field', 'goto' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $curr_additional_field,

			]
		);

		$this->add_control(
			'section_layout',
			[
				'label'   => __( 'Choose template', 'goto' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'template_one' => __( 'Template one', 'goto' ),
					'template_two' => __( 'Template two', 'goto' ),
				],

			]
		);
		$this->end_controls_section();
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_Tyto_AdditionalFields() );