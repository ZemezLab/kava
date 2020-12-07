<?php

namespace Elementor;

use ElementorTyto\Widgets\Widget_Advanced_Tyto_List;

class Widget_Advanced_Tyto_Search extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->_enqueue_styles();

        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_scripts_in_preview_mode']);
    }

    public function get_name()
    {
        return 'advanced-tyto-search';
    }

    public function get_categories()
    {
        return array('tyto');
    }

    public function get_title()
    {
        return esc_html__('Advanced Search', 'tyto');
    }

    public function get_icon()
    {
        return 'eicon-search';
    }

    protected function _register_controls()
    {
        $this->start_controls_section('ps_general', array(
            'label' => esc_html__('General', 'goto'),
        ));

        $this->add_control(
            'search_results_adv_list',
            [
                'label' => __('Use Advanced Tyto List', 'tyto'),
                'description' => __('works only on frontend', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),

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
                'label' => __('Use AJAX', 'tyto'),
                'description' => __('search without page reload', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
                'condition' => ['search_results_adv_list' => 'yes']
            ]
        );

        $this->add_control(
            'search_results_ajax_by_button',
            [
                'label' => __('AJAX Search by button click', 'tyto'),
                'description' => __('if disabled, then the search is triggered by pressing the keys', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
                'condition' => ['search_results_ajax' => 'yes']
            ]
        );

        $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1]);

        $this->add_control(
            'target_blank',
            [
                'label' => __('Show results on new page', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
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
            'search_input_title',
            [
                'label' => __('Search Title'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Keywords',
                'title' => __('Enter some text'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'search_input_placeholder',
            [
                'label' => __('Search Placeholder'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Enter a destination or tour name',
                'title' => __('Enter some text'),
            ]
        );

        $this->add_control(
            'search_autocomplete',
            [
                'label' => __('Search Autocomplete', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
            ]
        );

        $this->add_control(
            'only_autocomplete',
            [
                'label' => __('Use Only Autocomplete', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
                'condition' => ['search_autocomplete' => 'yes']
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'tyto'),
                'label_off' => __('Hide', 'tyto'),
                'default' => 'yes',
                'separator' => 'before',
                'condition' => ['search_results_adv_list!' => 'yes']
            ]
        );

        $this->add_control(
            'date_input_title',
            [
                'label' => __('Date Title'),
                'type' => Controls_Manager::TEXT,
                'default' => __('When', 'tyto'),
                'title' => __('Enter some text'),
                'condition' => ['show_date' => 'yes', 'search_results_adv_list!' => 'yes']
            ]
        );
        $this->add_control(
            'date_input_placeholder',
            [
                'label' => __('Date Placeholder'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Anytime', 'tyto'),
                'title' => __('Enter some text'),
                'condition' => ['show_date' => 'yes', 'search_results_adv_list!' => 'yes']
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => __('Show Categories', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'tyto'),
                'label_off' => __('Hide', 'tyto'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_categories_buttons',
            [
                'label' => __('Show Categories as Buttons', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'tyto'),
                'label_off' => __('Hide', 'tyto'),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'tags_input_title',
            [
                'label' => __('Categories Title'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Categories', 'tyto'),
                'title' => __('Enter some text', 'tyto'),
                'condition' => ['show_categories' => 'yes', 'show_categories_buttons!' => 'yes']
            ]
        );
        $this->add_control(
            'tags_input_placeholder',
            [
                'label' => __('Categories Placeholder'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Select Category',
                'title' => __('Enter some text'),
                'condition' => ['show_categories' => 'yes', 'show_categories_buttons!' => 'yes']
            ]
        );

        $tags = [];
        if ($tyto_tags = get_option('tyto_tags', false)) $tags = wp_list_pluck($tyto_tags, 'name', 'id');

        $this->add_control('search_tags', array(
            'type' => Controls_Manager::SELECT2,
            'label' => esc_html__('Categories'),
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
            'divider',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Search',
                'title' => __('Enter some text'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                [
                                    'name' => 'search_results_ajax',
                                    'operator' => '==',
                                    'value' => ''
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'search_results_ajax',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ],
                                [
                                    'name' => 'search_results_ajax_by_button',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('styles', array(
            'label' => esc_html__('Style', 'tyto'),
            'tab' => Controls_Manager::TAB_STYLE,
        ));

        /*BORDER RADIUS*/
        $this->add_control('radius', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Border radius', 'tyto'),
            'default' => array(
                'size' => 5,
            ),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                )
            ),
            'size_units' => array('px'),
            'selectors' => array(
                '{{WRAPPER}} .place-search-spn input' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .place-search-spn select[name="category"]' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .place-search-btn button' => 'border-radius: {{SIZE}}{{UNIT}};',
            ),
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
                '{{WRAPPER}} .place-search-spn:not(.place-search-spn--tags_buttons)' => 'margin-right: {{SIZE}}{{UNIT}};'
            ),
        ));

        /*INPUT BACKGROUND COLOR*/
        $this->add_control('input_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Input background', 'tyto'),
            'default' => '#0c3555',
            'selectors' => array(
                '{{WRAPPER}} .place-search-spn input' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn select' => 'background-color: {{VALUE}};'
            ),
        ));

        /*TEXT COLOR*/
        $this->add_control('input_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Input text, input icon, focused border color', 'tyto'),
            'default' => 'rgba(255,255,255,0.3)',
            'selectors' => array(
                '{{WRAPPER}} .place-search-spn input' => 'color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn input::placeholder' => 'color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn input:focus' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn select' => 'color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn select:focus' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn label:before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .place-search-spn label .icon' => 'color: {{VALUE}};',
            ),
        ));

        /*Title COLOR*/
        $this->add_control('title_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Title color', 'tyto'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .place-search-spn h5' => 'color: {{VALUE}};',

            ),
        ));

        /*Button Text COLOR*/
        $this->add_control('submit_text', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Submit Button Text', 'tyto'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .place-search-btn button' => 'color: {{VALUE}};',

            ),
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            [
                                'name' => 'search_results_ajax',
                                'operator' => '==',
                                'value' => ''
                            ]
                        ]
                    ],
                    [
                        'terms' => [
                            [
                                'name' => 'search_results_ajax',
                                'operator' => '==',
                                'value' => 'yes'
                            ],
                            [
                                'name' => 'search_results_ajax_by_button',
                                'operator' => '==',
                                'value' => 'yes'
                            ]
                        ]
                    ]
                ]
            ]
        ));

        /*SUBMIT BACKGROUND*/
        $this->add_control('submit_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Submit Button Background', 'tyto'),
            'default' => '#ec5849',
            'selectors' => array(
                '{{WRAPPER}} .place-search-btn button' => 'background-color: {{VALUE}};',
            ),
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            [
                                'name' => 'search_results_ajax',
                                'operator' => '==',
                                'value' => ''
                            ]
                        ]
                    ],
                    [
                        'terms' => [
                            [
                                'name' => 'search_results_ajax',
                                'operator' => '==',
                                'value' => 'yes'
                            ],
                            [
                                'name' => 'search_results_ajax_by_button',
                                'operator' => '==',
                                'value' => 'yes'
                            ]
                        ]
                    ]
                ]
            ]
        ));

        /*AUTOCOMPLETE BACKGROUND*/
        $this->add_control('autocomplete_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Autocomplete results background', 'tyto'),
            'default' => '#333',
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result' => 'background-color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes']
        ));
        $this->add_control('autocomplete_text_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Autocomplete text color', 'tyto'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result span' => 'color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes']
        ));

        $this->add_control('autocomplete_selected_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Autocomplete selected background', 'tyto'),
            'default' => '#000',
            'selectors' => array(
                '{{WRAPPER}} .autocomplete-result span.selected' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .autocomplete-result span:hover' => 'background-color: {{VALUE}};',
            ),
            'condition' => ['search_autocomplete' => 'yes']
        ));

        if (class_exists('Goto_Kirki')) {
            $primary_color = \Goto_Kirki::get_option('goto', 'primary_color');
            $second_color = \Goto_Kirki::get_option('goto', 'second_color');
        }

        $this->add_control('categories_buttons_text', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Categories Buttons Text', 'tyto'),
            'default' => $second_color ? $second_color : '#AAA',
            'selectors' => array(
                '{{WRAPPER}} .tag-button' => 'color: {{VALUE}};',
            ),
            'condition' => ['show_categories_buttons' => 'yes']
        ));

        $this->add_control('categories_buttons_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Categories Buttons Background', 'tyto'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .tag-button' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .tag-button.active' => 'color: {{VALUE}};',
            ),
            'condition' => ['show_categories_buttons' => 'yes']
        ));
        $this->add_control('categories_selected_buttons_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Categories Selected Buttons Background', 'tyto'),
            'default' => $second_color ? $second_color : '#AAA',
            'selectors' => array(
                '{{WRAPPER}} .tag-button.active' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .tag-button' => 'border-color: {{VALUE}};',
            ),
            'condition' => ['show_categories_buttons' => 'yes']
        ));

        $this->add_control( 'categories_buttons_align', array(
            'type'           => Controls_Manager::CHOOSE,
            'label'          => esc_html__( 'Buttons Alignment', 'tyto' ),
            'options'        => array(
                'left'   => array(
                    'title' => esc_html__( 'Left', 'tyto' ),
                    'icon'  => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'tyto' ),
                    'icon'  => 'fa fa-align-center'
                ),
                'right'  => array(
                    'title' => esc_html__( 'Right', 'tyto' ),
                    'icon'  => 'fa fa-align-right'
                ),
            ),
            'default'        => 'left',
            'selectors'      => array(
                '{{WRAPPER}} .place-search-spn--tags_buttons' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->end_controls_section();

    }

    public function _content_template()
    {
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

            if ($settings['show_categories'] && $settings['show_categories_buttons'] == 'yes') wp_enqueue_script('category-buttons');
        }

        if ($settings['search_results_adv_list'] !== 'yes' && $settings['show_date']) {
            wp_enqueue_script('moment');
            wp_enqueue_script('datepicker');
            wp_enqueue_style('datepicker');
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    var $homepage_search_dates = $('#adv-search-time');
                    $homepage_search_dates.daterangepicker({
                        autoUpdateInput: false,
                        locale: {
                            format: "DD.MM.YYYY",
                            applyLabel: 'Übernehmen',
                            cancelLabel: 'Abbrechen',
                            fromLabel: 'Abreise',
                            toLabel: 'Rückreise',
                            daysOfWeek: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
                            monthNames: ['Jan.', 'Feb.', 'März', 'Apr.', 'Mai', 'Jun.', 'Jul.', 'Aug.', 'Sep.', 'Okt.', 'Nov.', 'Dez.'],
                            firstDay: 1
                        }
                    });

                    $homepage_search_dates.on('apply.daterangepicker', function (ev, picker) {
                        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
                    });

                    $homepage_search_dates.on('cancel.daterangepicker', function (ev, picker) {
                        $(this).val('');
                    });
                })
            </script>
        <?php }
        if ($settings['search_autocomplete']) {
            wp_enqueue_script('search-autocomplete');
        }
        ?>
        <form action="<?php echo esc_url(get_the_permalink($settings['results_page'])); ?>"
              id="<?php echo $form_id ?>" <?php if ($settings['target_blank'] == 'yes') echo 'target="_blank"' ?>
              autocomplete="off">
            <div class="advanced-tyto-search"
                <?php if ($settings['adv_list_id']) echo 'data-adv_list_id="' . $settings['adv_list_id'] . '"' ?>
                <?php if ($settings['adv_list_id'] && $settings['search_results_ajax']) echo 'data-ajax_button="'.$settings['search_results_ajax_by_button'].'"'?>>
                <?php if ($settings['adv_list_id']) { ?>
                <input type="hidden" name="adv_list_id" value="<?php echo $settings['adv_list_id']?>">
                <?php } ?>
                <div class="place-search-spn <?php if ($settings['search_autocomplete']) echo 'autocomplete-field' ?>">
                    <?php if ($settings['search_input_title']) { ?>
                    <h5><?php esc_html_e($settings['search_input_title']); ?></h5>
                    <?php } ?>
                    <label for="i-dest" class="goto-icon-location">
                        <span class="material-icons-outlined icon">place</span>
                        <input id="i-dest" type="text"
                               placeholder="<?php esc_attr_e($settings['search_input_placeholder']); ?>"
                               name="keywords"
                               value="<?php if (isset($_GET['keywords'])) echo $_GET['keywords']?>">
                    </label>
                </div>
                <?php if ($settings['search_results_adv_list'] !== 'yes' && $settings['show_date']) { ?>
                    <div class="place-search-spn">
                        <?php if ($settings['date_input_title']) { ?>
                        <h5><?php esc_html_e($settings['date_input_title']); ?></h5>
                        <?php } ?>
                        <label for="adv-search-time" class="goto-icon-calendar-3">
                            <span class="material-icons-outlined icon">calendar_today</span>
                            <input id="adv-search-time" type="text"
                                   placeholder="<?php esc_attr_e($settings['date_input_placeholder']); ?>"
                                   name="start_date">
                        </label>
                    </div>
                <?php } ?>
                <?php if ($settings['show_categories'] && $settings['show_categories_buttons'] !== 'yes') { ?>
                    <div class="place-search-spn">
                        <?php if ($settings['tags_input_title']) { ?>
                        <h5><?php esc_html_e($settings['tags_input_title']); ?></h5>
                        <?php } ?>
                        <label for="i-tags" class="goto-icon-tag">
                            <span class="material-icons-outlined icon">local_offer</span>
                            <select id="i-tags" name="category">
                                <option value=""><?php esc_html_e($settings['tags_input_placeholder']); ?></option>
                                <?php if ($settings['search_tags']) {
                                    foreach ($settings['search_tags'] as $tag) { ?>
                                        <option value="<?php echo $tag ?>"
                                            <?php if ($search_tag) echo selected($tag == $search_tag) ?>
                                        ><?php echo $tag ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </label>
                    </div>
                <?php } ?>
                <?php if ($settings['search_results_ajax'] != 'yes' || ($settings['search_results_ajax'] == 'yes' && $settings['search_results_ajax_by_button'] == 'yes')) { ?>
                    <div class="place-search-btn">
                        <button type="submit" data-num="1"><?php esc_html_e($settings['button_text']); ?></button>
                    </div>
                <?php } ?>
                <?php if ($settings['show_categories'] && $settings['show_categories_buttons'] == 'yes') { ?>
                    <div class="break"></div>
                    <div class="place-search-spn place-search-spn--tags_buttons">
                        <?php foreach ($settings['search_tags'] as $tag) { ?>
                            <div class="tag-button <?php if (in_array($tag, $search_tags)) echo 'active' ?>"><?php echo $tag ?></div>
                        <?php } ?>
                    </div>
                    <input type="hidden" value="<?php echo $search_tag ?>" name="category" id="i-tags">
                <?php } ?>
            </div>
            <i class="error"></i>
            <input type="hidden" value="" name="selected">
        </form>
        <?php
        if ($settings['only_autocomplete']) { ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#<?php echo $form_id ?>').submit(function (e) {
                        if (!$(this).find('input[name="selected"]').val()) {
                            $(this).find('i.error').html('<?php _e('Bitte geben Sie eine Destination an.', 'tyto')?>');
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
    }

    public function _enqueue_styles()
    {
        wp_enqueue_style($this->get_name(), \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/css/styles.css');
//        wp_enqueue_style('font-material');
        wp_enqueue_style('font-material-outlined');
        wp_register_script('search-autocomplete', \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/js/search-autocomplete.js');
        wp_localize_script('search-autocomplete', 'TytoAjaxVars',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );
        wp_register_script('adv-list-handler', \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/js/advanced-list-handler.js');
        wp_register_script('category-buttons', \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/js/category-buttons.js');
    }

    public function enqueue_scripts_in_preview_mode()
    {
        wp_enqueue_script('search-autocomplete');
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Advanced_Tyto_Search());

