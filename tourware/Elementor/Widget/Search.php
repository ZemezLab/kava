<?php
namespace Tourware\Elementor\Widget;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget;
use Tourware\Path;

class Search extends Widget
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-search';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Search' );
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-search';
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function _register_controls()
    {
        $this->start_controls_section('ps_general', array(
            'label' => esc_html__('General', 'goto'),
        ));

        $this->addControl(new \Tourware\Elementor\Control\LayoutSelector('/search'));

        $this->add_control(
            'search_results_adv_list',
            [
                'label' => __('Use Advanced Listing', 'tourware'),
                'description' => __('works only on frontend', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),

            ]
        );

        $this->add_control('adv_list_id', array(
            'type' => Controls_Manager::TEXT,
            'label' => esc_html__('Advanced List ID'),
            'condition' => ['search_results_adv_list' => 'yes']
        ));

        $this->add_control(
            'search_results_ajax',
            [
                'label' => __('Use AJAX', 'tourware'),
                'description' => __('search without page reload', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),
                'condition' => ['search_results_adv_list' => 'yes']
            ]
        );

        $this->add_control(
            'search_results_ajax_by_button',
            [
                'label' => __('AJAX Search by button click', 'tourware'),
                'description' => __('if disabled, then the search is triggered by pressing the keys', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),
                'condition' => ['search_results_ajax' => 'yes']
            ]
        );

        $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1]);

        $this->add_control(
            'target_blank',
            [
                'label' => __('Show results on new page', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),
                'condition' => ['search_results_ajax!' => 'yes']
            ]
        );

        $this->add_control('results_page', array(
            'type' => Controls_Manager::SELECT2,
            'label' => esc_html__('Results Page'),
            'options' => wp_list_pluck($pages, 'post_title', 'ID'),
            'condition' => ['search_results_ajax!' => 'yes']
        ));

        $this->add_control(
            'heading_search_content',
            [
                'label' => __( 'Keywords', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'search_input_title',
            [
                'label' => __('Title', 'elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Keywords',
            ]
        );

        $this->add_control(
            'search_input_placeholder',
            [
                'label' => __('Placeholder', 'elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Wohin soll die Reise gehen?',
            ]
        );

        $this->add_control(
            'search_autocomplete',
            [
                'label' => __('Autocomplete', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),
            ]
        );

        $this->add_control(
            'only_autocomplete',
            [
                'label' => __('Use Only Autocomplete', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'elementor-pro'),
                'label_off' => __('No', 'elementor-pro'),
                'condition' => ['search_autocomplete' => 'yes']
            ]
        );

        $this->add_control(
            'heading_date_content',
            [
                'label' => __( 'Date', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'yes',
                'condition' => ['search_results_adv_list!' => 'yes']
            ]
        );

        $this->add_control(
            'date_input_title',
            [
                'label' => __('Title', 'elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Reisezeitraum', 'tourware'),
                'condition' => ['show_date' => 'yes', 'search_results_adv_list!' => 'yes']
            ]
        );
        $this->add_control(
            'date_input_placeholder',
            [
                'label' => __('Placeholder', 'elementor-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Jederzeit', 'tourware'),
                'title' => __('Enter some text'),
                'condition' => ['show_date' => 'yes', 'search_results_adv_list!' => 'yes']
            ]
        );

        $this->add_control(
            'heading_categories_content',
            [
                'label' => __( 'Categories', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_categories',
            [
                'label' => __('Kategorien anzeigen', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_categories_buttons',
            [
                'label' => __('Show as Buttons', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'no',
                'condition' => ['show_categories' => 'yes']
            ]
        );

        $this->add_control(
            'tags_input_title',
            [
                'label' => __('Title'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Categories', 'elementor-pro'),
                'condition' => ['show_categories' => 'yes', 'show_categories_buttons!' => 'yes']
            ]
        );
        $this->add_control(
            'tags_input_placeholder',
            [
                'label' => __('Placeholder'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Select Category',
                'title' => __('Enter some text'),
                'condition' => ['show_categories' => 'yes', 'show_categories_buttons!' => 'yes']
            ]
        );

        $tags = [];
        if ($tyto_tags = get_option('tyto_tags', false)) $tags = wp_list_pluck($tyto_tags, 'name', 'name');

        $this->add_control('search_tags', array(
            'type' => Controls_Manager::SELECT2,
            'label' => esc_html__('List'),
            'multiple' => true,
            'options' => $tags,
            'condition' => ['show_categories' => 'yes']
        ));

        $this->add_control('search_default_tag', array(
            'type' => Controls_Manager::SELECT2,
            'label' => esc_html__('Default Category'),
            'options' => $tags,
            'condition' => ['show_categories' => 'yes', 'search_tags!' => '']
        ));

        $this->add_control(
            'heading_button_content',
            [
                'label' => __( 'Button', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => __( 'Type', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'icon' => __( 'Icon', 'elementor-pro' ),
                    'text' => __( 'Text', 'elementor-pro' ),
                ],
                'prefix_class' => 'elementor-search-form--button-type-',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Search', 'elementor-pro' ),
                'separator' => 'after',
                'condition' => [
                    'button_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'elementor-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'search',
                'options' => [
                    'search' => [
                        'title' => __( 'Search', 'elementor-pro' ),
                        'icon' => 'eicon-search',
                    ],
                    'arrow-right' => [
                        'title' => __( 'Arrow', 'elementor-pro' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                ],
                'render_type' => 'template',
                'prefix_class' => 'elementor-search-form--icon-',
                'condition' => [
                    'button_type' => 'icon',
                ],
            ]
        );

//        $this->add_control(
//            'button_text',
//            [
//                'label' => __('Button Text'),
//                'type' => Controls_Manager::TEXT,
//                'default' => 'Search',
//                'title' => __('Enter some text'),
//                'conditions' => [
//                    'relation' => 'or',
//                    'terms' => [
//                        [
//                            'terms' => [
//                                [
//                                    'name' => 'search_results_ajax',
//                                    'operator' => '==',
//                                    'value' => ''
//                                ]
//                            ]
//                        ],
//                        [
//                            'terms' => [
//                                [
//                                    'name' => 'search_results_ajax',
//                                    'operator' => '==',
//                                    'value' => 'yes'
//                                ],
//                                [
//                                    'name' => 'search_results_ajax_by_button',
//                                    'operator' => '==',
//                                    'value' => 'yes'
//                                ]
//                            ]
//                        ]
//                    ]
//                ]
//            ]
//        );

        $this->end_controls_section();

        $this->start_controls_section('styles', array(
            'label' => esc_html__('Style', 'elementor-pro'),
            'tab' => Controls_Manager::TAB_STYLE,
        ));

        $this->add_control('space', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Space'),
            'default' => array(
                'size' => 10
            ),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 30,
                    'step' => 1
                ),
            ),
            'size_units' => array('px'),
            'selectors' => array(
                '{{WRAPPER}} .place-search-spn:not(.place-search-spn--tags_buttons)' => 'margin-right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .tag-button:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};'
            ),
        ));

        $this->add_control(
            'categories_buttons_style_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Categories Buttons', 'tourware' ),
                'condition' => ['show_categories_buttons' => 'yes'],
                'separator' => 'before'
            ]
        );
        $this->add_control('categories_selected_buttons_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Selected Buttons Background', 'tourware'),
            'selectors' => array(
                '{{WRAPPER}} .tag-button.active' => 'background-color: {{VALUE}}; border-color: {{VALUE}}',
            ),
            'condition' => ['show_categories_buttons' => 'yes']
        ));

        $this->add_control( 'categories_buttons_align', array(
            'type'           => Controls_Manager::CHOOSE,
            'label'          => esc_html__( 'Alignment', 'elementor-pro' ),
            'options'        => array(
                'left'   => array(
                    'title' => esc_html__( 'Left', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-center'
                ),
                'right'  => array(
                    'title' => esc_html__( 'Right', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-right'
                ),
            ),
            'default'        => 'left',
            'selectors'      => array(
                '{{WRAPPER}} .place-search-spn--tags_buttons' => 'text-align: {{VALUE}};'
            ),
            'condition' => ['show_categories_buttons' => 'yes']
        ));

        /*AUTOCOMPLETE COLORS*/
        $this->add_control(
            'autocomplete_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Autocomplete', 'elementor' ),
                'condition' => ['search_autocomplete' => 'yes'],
                'separator' => 'before'
            ]
        );
        $this->add_control('autocomplete_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Results background', 'tourware'),
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result' => 'background-color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes'],
        ));
        $this->add_control('autocomplete_text_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Text color', 'elementor-pro'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result span' => 'color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes']
        ));

        $this->add_control('autocomplete_selected_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Selected Item background', 'tourware'),
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result span.selected' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .autocomplete-result span:hover' => 'background-color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes']
        ));

        $this->end_controls_section();

        $this->addControlGroupField(['id' => 'search_input']);
        $this->addControlGroupIcon(['id' => 'field_icon', 'Fields Icons']);

        $this->addControlGroupButton([
            'id' => 'submit_button',
            'label' => 'Submit Button',
            'selector' => '.place-search-btn .elementor-button',
        ]);

        $this->addControlGroupButton([
            'id' => 'categories_buttons',
            'label' => 'Categories Buttons',
            'selector' => '.elementor-button.tag-button',
            'condition' => ['show_categories_buttons' => 'yes']
        ]);

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $form_id = uniqid('advanced-search-');

        if ($settings['show_categories']) {
            if ($settings['search_default_tag'] && $settings['adv_list_id']) {
                set_query_var('advanced_search_category_' . $settings['adv_list_id'], $settings['search_default_tag']);
            }

            if ($settings['adv_list_id'] && $settings['search_results_ajax'] == 'yes') wp_enqueue_script('adv-list-handler');

            if (isset($_GET['category']))
                $search_tag = $_GET['category'];
            else if ($settings['search_default_tag'])
                $search_tag = get_query_var('advanced_search_category_' . $settings['adv_list_id']);
            if ($search_tag) $search_tags = explode(',', urldecode($search_tag));

            if ($settings['show_categories'] && $settings['show_categories_buttons'] == 'yes')
                wp_enqueue_script('category-buttons');
        }

        if ($settings['search_results_adv_list'] !== 'yes' && $settings['show_date']) {
            wp_enqueue_script('moment');
            wp_enqueue_script('datepicker');
            wp_enqueue_style('datepicker');
            wp_enqueue_script('init-datepicker');
        }
        if ($settings['search_autocomplete']) {
            wp_enqueue_script('search-autocomplete');
        }
        if ($settings['only_autocomplete']) { ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#<?php echo $form_id ?>').submit(function (e) {
                        if (!$(this).find('input[name="selected"]').val()) {
                            $(this).find('i.error').html('<?php _e('Bitte geben Sie eine Destination an.', 'tourware')?>');
                            return false;
                        } else {
                            return true;
                        }
                    });
                    $('#<?php echo $form_id ?>').keydown(function () {
                        $(this).find('i.error').empty();
                    })
                })
            </script>
        <?php }
        include Path::getResourcesFolder().'layouts/search/template.php';
    }

    public function _enqueue_styles()
    {
        wp_register_script('search-autocomplete', Path::getResourcesUri() . '/js/widget/search/search-autocomplete.js');
        wp_localize_script('search-autocomplete', 'TytoAjaxVars',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );
        wp_register_script('adv-list-handler', Path::getResourcesUri() . '/js/widget/search/advanced-list-handler.js');
        wp_register_script('category-buttons', Path::getResourcesUri() . '/js/widget/search/category-buttons.js');
        wp_register_script('init-datepicker', Path::getResourcesUri() .  '/js/widget/search/init-datepicker.js', ['datepicker']);
    }
}
