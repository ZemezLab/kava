<?php

namespace Tourware\Elementor\Widget\Flightprice;

use Tourware\Elementor\Widget\Table\AbstractTable;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

class Table extends AbstractTable
{
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-flightprice-table';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __('Flightprice Table');
    }

    public function renderTable($settings)
    {
        $destinations = $this->getDestinationsForTable($settings['destinations']);
        $modal = '';

        echo '<table>';

        echo '<thead>';
        echo '<th>Zielort</th>';
        echo '<th>Airline</th>';
        echo '<th>Buchungszeitraum</th>';
        echo '<th>Abflug</th>';
        echo '<th>Preis ab</th>';
        echo '<th></th>';
        echo '<th></th>';
        echo '</thead>';

        echo '<tbody>';
        foreach ($destinations as $destination) {
            $modalClose = '<i class="fa fa-times fp-modal-close"></i>';
            $modal .= '<div class="fp-modal" data-id="' . $destination->fpreise_id . '">';
            $modal .= '<div class="fp-modal-content">' . $modalClose . nl2br($destination->fpreise_besonderheit) . '</div>';
            $modal .= '</div>';

            echo '<tr>';
            echo '<td>' . nl2br($destination->zielort) . '</td>';
            echo '<td><img class="fpreise-airline-img" src="/' . $destination->airline_img . '" width="30" height="30"/></td>';
            echo '<td>' . nl2br($destination->fpreise_zeitraum) . '</td>';
            echo '<td>' . nl2br($destination->fpreise_abflug) . '</td>';
            echo '<td>' . nl2br($destination->fpreise_flugpreis) . '</td>';
            echo '<td><i class="fa fa-info-circle fp-modal-open" data-id="' . $destination->fpreise_id . '"></i></td>';
            echo '<td>' . $this->getButton($destination->fpreise_id) . '</td>';
            echo '</tr>';
        }
        echo '</body>';

        echo '</table>';
        echo $modal;
    }

    protected function _register_controls()
    {
        $this->sectionQuery();
        $this->sectionButton();
        $this->sectionStyleButton();
        $this->sectionStyleIcon();

        parent::_register_controls();
    }

    public function _enqueue_styles()
    {
        wp_enqueue_script('fp-script', \Tourware\Path::getResourcesUri() . 'js/widget/flightprice/script.js', ['jquery']);
        parent::_enqueue_styles();
    }

    private function getButton($id)
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'fp-ep-button-wrapper');

        if (!empty($settings['link']['url'])) {
            $add = stripos($settings['link']['url'], '?') !== false ? '&id=' : '?id=';
            $this->add_render_attribute('advanced_button', 'href', $settings['link']['url'] . $add . $id);

            if ($settings['link']['is_external']) {
                $this->add_render_attribute('advanced_button', 'target', '_blank');
            }

            if ($settings['link']['nofollow']) {
                $this->add_render_attribute('advanced_button', 'rel', 'nofollow');
            }

        }

        if ($settings['link']['nofollow']) {
            $this->add_render_attribute('advanced_button', 'rel', 'nofollow');
        }

        if ($settings['onclick']) {
            $this->add_render_attribute('advanced_button', 'onclick', $settings['onclick_event']);
        }

        if ($settings['attention_button']) {
            $this->add_render_attribute('advanced_button', 'class', 'fp-ep-attention-button');
        }

        $this->add_render_attribute('advanced_button', 'class', [
            'fp-ep-button',
            'fp-ep-button-effect-' . esc_attr($settings['button_effect']),
            'fp-ep-button-size-' . esc_attr($settings['button_size']),
        ]);


        if ($settings['hover_animation']) {
            $this->add_render_attribute('advanced_button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('advanced_button', 'id', $settings['button_css_id']);
        }

        $render = '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        $render .= '<a ' . $this->get_render_attribute_string('advanced_button') . '>' . $this->getButtonText() . '</a>';
        $render .= '</div>';

        return $render;
    }

    private function getButtonText()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('content-wrapper', 'class', 'fp-ep-button-content-wrapper');

        if ('left' == $settings['icon_align'] or 'right' == $settings['icon_align']) {
            $this->add_render_attribute('content-wrapper', 'class', 'fp-flex fp-flex-middle fp-flex-center');
        }
        $this->add_render_attribute('content-wrapper', 'class', ('top' == $settings['icon_align']) ? 'fp-flex fp-flex-column' : '');
        $this->add_render_attribute('content-wrapper', 'class', ('bottom' == $settings['icon_align']) ? 'fp-flex fp-flex-column-reverse' : '');
        $this->add_render_attribute('content-wrapper', 'data-text', esc_attr($settings['text']));

        $this->add_render_attribute('icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align']);
        $this->add_render_attribute('icon-align', 'class', 'fp-ep-button-icon');

        $this->add_render_attribute('text', 'class', 'fp-ep-button-text');
        $this->add_inline_editing_attributes('text', 'none');

        $migrated = isset($settings['__fa4_migrated']['button_icon']);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        ob_start();
        ?>
      <div <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>
          <?php if (!empty($settings['button_icon']['value'])) : ?>
            <div
                class="fp-ep-button-icon fp-flex-center fp-flex-align-<?php echo esc_attr($settings['icon_align']); ?>">
              <div class="fp-ep-button-icon-inner">

                  <?php if ($is_new || $migrated) :
                      Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']);
                  else : ?>
                    <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                  <?php endif; ?>

              </div>
            </div>
          <?php endif; ?>
        <div <?php echo $this->get_render_attribute_string('text'); ?>>

          <span class="avdbtn-text"><?php echo esc_html($settings['text']); ?></span>

            <?php if ('g' == $settings['button_effect']) : ?>
              <span class="avdbtn-alt-text"><?php echo esc_html($settings['text']); ?></span>
            <?php endif; ?>
        </div>

      </div>
        <?php
        return ob_get_clean();
    }

    private function getDestinationsForWidget()
    {
        global $wpdb;

        return wp_list_pluck(
            $wpdb->get_results(
                "SELECT * FROM `web18mts_fpreise_values`
                WHERE `select_type` = 'land'
                AND `select_del` = 0 ORDER BY select_value"
            ),
            'select_value',
            'select_id'
        );
    }

    private function sectionQuery()
    {
        $this->start_controls_section('section_content_query', [
            'label' => __('Query', 'tourware'),
        ]);

        $this->add_control('destinations', [
            'type' => Controls_Manager::SELECT2,
            'label' => __('Destinations', 'tourware'),
            'multiple' => true,
            'options' => $this->getDestinationsForWidget(),
        ]);

        $this->add_control('order_by', [
            'type' => Controls_Manager::SELECT,
            'label' => __('Order By', 'tourware'),
            'default' => 0,
            'options' => [
                0 => __('Zielort', 'tourware'),
                1 => __('Airline', 'tourware'),
                2 => __('Buchungszeitraum', 'tourware'),
                3 => __('Abflug', 'tourware'),
                4 => __('Preise ab', 'tourware'),
            ],
        ]);

        $this->add_control('order', [
            'type' => Controls_Manager::SELECT,
            'label' => __('Order', 'tourware'),
            'default' => 'desc',
            'options' => [
                'asc' => __('ASC', 'tourware'),
                'desc' => __('DESC', 'tourware'),
            ],
        ]);

        $this->end_controls_section();
    }

    private function sectionButton()
    {
        $this->start_controls_section('section_button', [
            'label' => esc_html__('Button', 'tourware'),
        ]);

        $this->add_control('text', [
            'label' => esc_html__('Text', 'tourware'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => esc_html__('Click me', 'tourware'),
            'placeholder' => esc_html__('Click me', 'tourware'),
        ]);

        $this->add_control('link', [
            'label' => esc_html__('Link', 'tourware'),
            'type' => Controls_Manager::URL,
            'dynamic' => ['active' => true],
            'placeholder' => esc_html__('https://your-link.com', 'tourware'),
            'default' => [
                'url' => '#',
            ],
        ]);

        $this->add_control('button_size', [
            'label' => esc_html__('Button Size', 'tourware'),
            'type' => Controls_Manager::SELECT,
            'default' => 'md',
            'options' => [
                'xs' => esc_html__('Extra Small', 'tourware'),
                'sm' => esc_html__('Small', 'tourware'),
                'md' => esc_html__('Medium', 'tourware'),
                'lg' => esc_html__('Large', 'tourware'),
                'xl' => esc_html__('Extra Large', 'tourware'),
            ],
        ]);

        $this->add_responsive_control('align', [
            'label' => esc_html__('Alignment', 'tourware'),
            'type' => Controls_Manager::CHOOSE,
            'prefix_class' => 'elementor%s-align-',
            'default' => '',
            'options' => [
                'left' => [
                    'title' => __('Left', 'tourware'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'tourware'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'tourware'),
                    'icon' => 'eicon-text-align-right',
                ],
                'justify' => [
                    'title' => __('Justified', 'tourware'),
                    'icon' => 'eicon-text-align-justify',
                ],
            ],
        ]);

        $this->add_control('button_icon', [
            'label' => esc_html__('Icon', 'tourware'),
            'type' => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'label_block' => false,
            'skin' => 'inline'
        ]);

        $this->add_control('icon_align', [
            'label' => esc_html__('Icon Position', 'tourware'),
            'type' => Controls_Manager::SELECT,
            'default' => 'right',
            'options' => [
                'left' => esc_html__('Left', 'tourware'),
                'right' => esc_html__('Right', 'tourware'),
                'top' => esc_html__('Top', 'tourware'),
                'bottom' => esc_html__('Bottom', 'tourware'),
            ],
            'condition' => [
                'button_icon[value]!' => '',
            ],
        ]);

        $this->add_control('icon_indent', [
            'label' => esc_html__('Icon Spacing', 'tourware'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                ],
            ],
            'default' => [
                'size' => 8,
            ],
            'condition' => [
                'button_icon[value]!' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button .fp-flex-align-right' => is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .fp-ep-button .fp-flex-align-left' => is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .fp-ep-button .fp-flex-align-top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .fp-ep-button .fp-flex-align-bottom' => 'margin-top: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('button_css_id', [
            'label' => __('Button ID', 'elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => '',
            'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'tourware'),
            'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor'),
            'separator' => 'before',

        ]);

        $this->end_controls_section();
    }

    private function sectionStyleButton()
    {
        $this->start_controls_section('section_content_style', [
            'label' => esc_html__('Button', 'tourware'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_effect', [
            'label' => esc_html__('Effect', 'tourware'),
            'type' => Controls_Manager::SELECT,
            'default' => 'a',
            'options' => [
                'a' => esc_html__('Effect A', 'tourware'),
                'b' => esc_html__('Effect B', 'tourware'),
                'c' => esc_html__('Effect C', 'tourware'),
                'd' => esc_html__('Effect D', 'tourware'),
                'e' => esc_html__('Effect E', 'tourware'),
                'f' => esc_html__('Effect F', 'tourware'),
                'g' => esc_html__('Effect G', 'tourware'),
                'h' => esc_html__('Effect H', 'tourware'),
                'i' => esc_html__('Effect I', 'tourware'),
            ],
            'render_type' => 'template',
        ]);

        $this->add_control('attention_button', [
            'label' => esc_html__('Attention', 'tourware'),
            'type' => Controls_Manager::SWITCHER,
        ]);

        $this->start_controls_tabs('tabs_advanced_button_style');

        $this->start_controls_tab('tab_advanced_button_normal', [
            'label' => esc_html__('Normal', 'tourware'),
        ]);

        $this->add_control('advanced_button_text_color', [
            'label' => esc_html__('Text Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'button_background',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .fp-ep-button, 
								{{WRAPPER}} .fp-ep-button.fp-ep-button-effect-i .fp-ep-button-content-wrapper:after,
								{{WRAPPER}} .fp-ep-button.fp-ep-button-effect-i .fp-ep-button-content-wrapper:before,
								{{WRAPPER}} .fp-ep-button.fp-ep-button-effect-h:hover',
        ]);

        $this->add_control('button_border_style', [
            'label' => esc_html__('Border Style', 'tourware'),
            'type' => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'none' => esc_html__('None', 'tourware'),
                'solid' => esc_html__('Solid', 'tourware'),
                'dotted' => esc_html__('Dotted', 'tourware'),
                'dashed' => esc_html__('Dashed', 'tourware'),
                'groove' => esc_html__('Groove', 'tourware'),
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'border-style: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('button_border_width', [
            'label' => esc_html__('Border Width', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => 3,
                'right' => 3,
                'bottom' => 3,
                'left' => 3,
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'button_border_style!' => 'none'
            ]
        ]);

        $this->add_control('button_border_color', [
            'label' => esc_html__('Border Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'default' => '#666',
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'button_border_style!' => 'none'
            ],
        ]);

        $this->add_responsive_control('advanced_button_radius', [
            'label' => esc_html__('Border Radius', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('advanced_button_padding', [
            'label' => esc_html__('Padding', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'advanced_button_shadow',
            'selector' => '{{WRAPPER}} .fp-ep-button',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'advanced_button_typography',
            'selector' => '{{WRAPPER}} .fp-ep-button',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('tab_advanced_button_hover', [
            'label' => esc_html__('Hover', 'tourware'),
        ]);

        $this->add_control('advanced_button_hover_text_color', [
            'label' => esc_html__('Text Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'button_hover_background',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .fp-ep-button:after, 
								{{WRAPPER}} .fp-ep-button:hover,
								{{WRAPPER}} .fp-ep-button.fp-ep-button-effect-i,
								{{WRAPPER}} .fp-ep-button.fp-ep-button-effect-h:after',
        ]);

        $this->add_control('button_hover_border_style', [
            'label' => esc_html__('Border Style', 'tourware'),
            'type' => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'none' => esc_html__('None', 'tourware'),
                'solid' => esc_html__('Solid', 'tourware'),
                'dotted' => esc_html__('Dotted', 'tourware'),
                'dashed' => esc_html__('Dashed', 'tourware'),
                'groove' => esc_html__('Groove', 'tourware'),
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover' => 'border-style: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('button_hover_border_width', [
            'label' => esc_html__('Border Width', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => 3,
                'right' => 3,
                'bottom' => 3,
                'left' => 3,
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'button_hover_border_style!' => 'none'
            ]
        ]);

        $this->add_control('button_hover_border_color', [
            'label' => esc_html__('Border Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'button_hover_border_style!' => 'none'
            ]
        ]);

        $this->add_responsive_control('advanced_button_hover_radius', [
            'label' => esc_html__('Border Radius', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'advanced_button_hover_shadow',
            'selector' => '{{WRAPPER}} .fp-ep-button:hover',
        ]);

        $this->add_control('hover_animation', [
            'label' => esc_html__('Hover Animation', 'tourware'),
            'type' => Controls_Manager::HOVER_ANIMATION,
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function sectionStyleIcon()
    {
        $this->start_controls_section('section_style_icon', [
            'label' => esc_html__('Icon', 'tourware'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'button_icon[value]!' => '',
            ],
        ]);

        $this->start_controls_tabs('tabs_advanced_button_icon_style');

        $this->start_controls_tab('tab_advanced_button_icon_normal', [
            'label' => esc_html__('Normal', 'tourware'),
        ]);

        $this->add_control('advanced_button_icon_color', [
            'label' => esc_html__('Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'advanced_button_icon_background',
            'selector' => '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'advanced_button_icon_border',
            'placeholder' => '1px',
            'default' => '1px',
            'selector' => '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner',
        ]);

        $this->add_responsive_control('advanced_button_icon_radius', [
            'label' => esc_html__('Border Radius', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('advanced_button_icon_padding', [
            'label' => esc_html__('Padding', 'tourware'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'advanced_button_icon_shadow',
            'selector' => '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner',
        ]);

        $this->add_responsive_control('advanced_button_icon_size', [
            'label' => __('Icon Size', 'tourware'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 10,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button .fp-ep-button-icon .fp-ep-button-icon-inner' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('tab_advanced_button_icon_hover', [
            'label' => esc_html__('Hover', 'tourware'),
        ]);

        $this->add_control('advanced_button_hover_icon_color', [
            'label' => esc_html__('Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover .fp-ep-button-icon i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .fp-ep-button:hover .fp-ep-button-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'advanced_button_icon_hover_background',
            'selector' => '{{WRAPPER}} .fp-ep-button:hover .fp-ep-button-icon .fp-ep-button-icon-inner',
        ]);

        $this->add_control('icon_hover_border_color', [
            'label' => esc_html__('Border Color', 'tourware'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'advanced_button_icon_border_border!' => ''
            ],
            'selectors' => [
                '{{WRAPPER}} .fp-ep-button:hover .fp-ep-button-icon .fp-ep-button-icon-inner' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function getDestinationsForTable($landId)
    {
        global $wpdb;

        return $wpdb->get_results(
            "SELECT * FROM web18mts_fpreise as fpreise
            INNER JOIN web18mts_fpreise_values as fpreise_values
                ON fpreise.fpreise_airline = fpreise_values.select_id
            INNER JOIN (SELECT `select_value` as zielort, select_id as zielort_select_id FROM web18mts_fpreise_values WHERE `select_del` = 0 AND select_type = 'flughafen') as zielorte
                ON zielorte.zielort_select_id = fpreise.fpreise_flughafen
            INNER JOIN (SELECT `select_special` as airline_img,`select_value` as airline_name, select_id as airline_select_id FROM web18mts_fpreise_values WHERE `select_type` = 'airline' AND `select_del` = 0) as airline
                ON airline.airline_select_id = fpreise.fpreise_airline
            WHERE `fpreise_land` IN (" . implode(",", $landId) . ")
                AND `fpreise_hide` = 0"
        );
    }

}