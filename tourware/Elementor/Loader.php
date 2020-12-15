<?php

namespace Tourware\Elementor;

use Elementor\Plugin;
use ElementorTyto\Widgets\Widget_Advanced_Tyto_List;
use Tourware\Path;

/**
 * Class Loader
 *
 * Main Loader class
 * @since 1.2.0
 */
class Loader {
    /**
     * Instance
     *
     * @since 1.2.0
     * @access private
     * @static
     *
     * @var Loader The single instance of the class.
     */
    private static $_instance = null;

    /**
     *  Loader class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.2.0
     * @access public
     */
    public function __construct()
    {
        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        // Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_widget_scripts' ] );
        // Register category
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );
        // Scripts in Preview mode
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_scripts_in_preview_mode'] );
        // editor styles
        add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'enqueue_editor'] );
        // Register locations
        add_action( 'elementor/theme/register_locations', [ $this, 'register_elementor_locations' ] );
        // Register Dynamic Tags
        add_action( 'elementor/dynamic_tags/register_tags', [$this, 'register_elementor_dynamic_tags'] );

        add_action('wp_ajax_search_autocomplete', [ $this, 'search_autocomplete' ]);
        add_action('wp_ajax_nopriv_search_autocomplete', [ $this, 'search_autocomplete' ]);
        add_action('wp_ajax_adv_list_pagination', [$this, 'adv_list_pagination']);
        add_action('wp_ajax_nopriv_adv_list_pagination', [$this, 'adv_list_pagination']);
        add_action('wp_ajax_multistep_mail_send', [$this, 'multistep_mail_send']);
        add_action('wp_ajax_nopriv_multistep_mail_send', [$this, 'multistep_mail_send']);

        add_action( 'get_footer', [$this, 'enqueue_styles'] );
    }

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.2.0
     * @access public
     *
     * @return Loader An instance of the class.
     */
    public static function getInstance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function getElementorFolder()
    {
        return get_parent_theme_file_path() . '/elementor/'; // @todo: get rid of this!
    }

    public static function getElementorFolderUri()
    {
        return get_parent_theme_file_uri() . '/elementor/'; // @todo: get rid of this!
    }

    public static function getElementorWidgetsFolderUri()
    {
        return get_parent_theme_file_uri() . '/elementor/widgets/'; // @todo: get rid of this!
    }

    /**
     * widget_scripts
     *
     * Load required Loader core files.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widget_scripts() {
        /* GRID */
        wp_register_style('tyto-grid', self::getElementorFolderUri() . '/assets/css/grid.css');
        wp_register_style('tyto-pagination', self::getElementorFolderUri() . '/assets/css/pagination.css');

        /* SLICK SLIDER */
        wp_register_script('slick-script', self::getElementorFolderUri() . '/assets/js/slick.min.js', array('jquery'), false, true);
        wp_register_style('slick-style', self::getElementorFolderUri() . '/assets/css/slick.css');
        /* DATEPICKER */
        wp_register_script( 'moment', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery'));
        wp_register_script( 'datepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array('jquery', 'moment'));
        wp_register_style('datepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css');
        /*TINY SLIDER*/
        wp_register_script('tiny-slider-js',self::getElementorFolderUri() . '/assets/js/tiny-slider.js', array(),null,true);
        wp_register_style('tiny-slider',self::getElementorFolderUri() . '/assets/css/tiny-slider.css');
        /* ISOTOPE */
        wp_register_script('isotope-script', self::getElementorFolderUri() . '/assets/js/isotope.min.js', array('jquery'), false, true);
        /* COLLAPSER */
        wp_register_script('collapser-script', self::getElementorFolderUri() . '/assets/js/jquery.collapser.min.js', array('jquery'), false, true);
        /* LAZY LOAD */
        wp_enqueue_script('lazysizes-script', self::getElementorFolderUri() . '/assets/js/lazysizes.min.js', array('jquery'), false, true);

        wp_register_script('tyto-preview-script', self::getElementorFolderUri() . '/assets/js/elementor-preview.js', array('jquery'), false, true);
    }

    /**
     * widget_scripts
     *
     * Load required Loader core files.
     *
     * @since 1.2.0
     * @access public
     */

    public function register_categories( $elements_manager ) {
        $elements_manager->add_category(
            'tyto',
            array(
                'title' => esc_html__( 'tourware' )
            )
        );
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widgets() {
        // Its is now safe to include Widgets files
        $this->include_widgets_files();
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.2.0
     * @access private
     */
    private function include_widgets_files() {
        $widgets_path = get_parent_theme_file_path() . '/elementor/widgets/';
        $widgets      = glob( $widgets_path . '*.php' );

        foreach ( $widgets as $key ) {
            if ( file_exists( $key ) ) {
                require_once $key;
            }
        }

        $widgets_path = get_parent_theme_file_path() . '/elementor/widgets/*/';
        $widgets      = glob( $widgets_path . '*.php' );
        foreach ( $widgets as $key ) {
            if ( file_exists( $key ) ) {
                require_once $key;
            }
        }
    }

    private function include_dynamic_tags_files() {
        $widgets_path = get_parent_theme_file_path() . '/elementor/dynamic_tags/';
        $widgets      = glob( $widgets_path . '*.php' );

        foreach ( $widgets as $key ) {
            if ( file_exists( $key ) ) {
                require_once $key;
            }
        }

        $widgets_path = get_parent_theme_file_path() . '/elementor/dynamic_tags/*/';
        $widgets      = glob( $widgets_path . '*.php' );
        foreach ( $widgets as $key ) {
            if ( file_exists( $key ) ) {
                require_once $key;
            }
        }
    }

    public function enqueue_scripts_in_preview_mode() {
        wp_enqueue_style('tyto-grid');
        wp_enqueue_style('tyto-pagination');
//        wp_enqueue_style('tyto-ionicons');

        wp_enqueue_script('slick-script');
        wp_enqueue_script('tyto-preview-script');
        wp_enqueue_style('slick-style');

        wp_enqueue_script('isotope-script');

        wp_enqueue_script('collapser-script');

        wp_enqueue_script( 'moment');
        wp_enqueue_script( 'datepicker');
        wp_enqueue_style('datepicker');

        wp_enqueue_script('tiny-slider-js');
        wp_enqueue_style('tiny-slider');
    }

    public function enqueue_editor() {
//        wp_enqueue_style('tyto-elementor-editor-css', get_parent_theme_file_uri() . '/tourware-resources/editor.css');
    }

    public function enqueue_styles() {
        wp_enqueue_style('tyto-grid');
        wp_enqueue_style('tyto-pagination');
        wp_enqueue_style('tyto-ionicons');
    }

    public function register_elementor_locations($elementor_theme_manager) {
        $elementor_theme_manager->register_all_core_location();
    }

    public function register_elementor_dynamic_tags( $dynamic_tags ) {
        // In our Dynamic Tag we use a group named request-variables so we need
        // To register that group as well before the tag
        Plugin::$instance->dynamic_tags->register_group( 'request-variables', [
            'title' => 'Request Variables'
        ] );

        $this->include_dynamic_tags_files();
        // Finally register the tag
        $dynamic_tags->register_tag( 'Elementor_Server_Var_Tag' );
    }

    public static function search_autocomplete() {
        global $wpdb;
        $search_str = $_POST['search_str'];
        $search_res = [];
        if ($search_str) {
            $search_res = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'posts
                         WHERE (post_type = "ht_dest" OR post_type = "tytodeestinations")  
                         AND post_title LIKE "'.$search_str.'%"
                         AND post_status = "publish" 
                         ORDER BY post_title ASC');
        }

        echo json_encode($search_res);
        die;
    }

    public static function multistep_mail_send()
    {
        $post = $_POST;

        $mailjet = get_field('mailjet', $post['page_id']);

        if ($mailjet[0]['email_to']) {
            $mail_to = $mailjet[0]['email_to'];
        }
        if (!$mail_to) {
            $mail_to = get_option('admin_email');
        }
        if (!$mail_to) {
            return 'fail';
        }

        $variables = [];
        foreach ($post['questions'] as $q_id => $question) {
            $variables['question'.$q_id.'title'] = $post['questions'][$q_id]['title'];
            $variables['question'.$q_id.'answer'] = $post['questions'][$q_id]['answer'];
        }

        $variables['index'] = $post['index'];
        $variables['sex'] = $post['sex'];
        $variables['first_name'] = $post['first_name'];
        $variables['last_name'] = $post['last_name'];
        $variables['email'] = $post['email'];
        $variables['phone'] = $post['phone'];
        $variables['keywords'] = $post['keywords'];


        $data = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $mailjet[0]['email_from'],
                        'Name' => $mailjet[0]['email_name']
                    ],
                    'To' => [
                        [
                            'Email' => $mail_to,
                        ]
                    ],
                    "Variables" => $variables,
                    'Subject' => $mailjet[0]['subject'],
                    'TemplateID' => (int)$mailjet[0]['template_id_for_admin'],
                    "TemplateLanguage" => true,
                ]
            ]
        ];

        $mail = json_decode(self::mailjet($data, $mailjet[0]['public_key'], $mailjet[0]['private_key']));

        $data['Messages'][0]['To'][0]['Email'] = $post['email'];
        $data['Messages'][0]['TemplateID'] = (int)$mailjet[0]['template_id_for_user'];
        $mail_for_user = json_decode(self::mailjet($data, $mailjet[0]['public_key'], $mailjet[0]['private_key']));

        if ($mail->Messages[0]->Status == 'success') {
            echo 'success';
        } else {
            echo 'fail';
        }
        die();
    }

    function mailjet($data, $public_key, $private_key)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailjet.com/v3.1/send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_USERPWD, $public_key . ':' . $private_key);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function adv_list_pagination() {
        $elementor = Plugin::instance();
        if ( version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
            $elements = $elementor->documents->get( $_POST['post_ID'] )->get_elements_data();
        } else {
            $elements = $elementor->db->get_plain_editor( $_POST['post_ID'] );
        }
        $widget_element = Loader::tyto_find_element_recursive( $elements, $_POST['widget_id'] );
        if ( !empty($widget_element)) $widget = $elementor->elements_manager->create_element_instance( $widget_element );
        $settings = $widget->get_settings();

        $args = wp_unslash($_POST['args']);

        if (isset($args['meta_query']['search_tag']))
            $args['meta_query']['search_tag']['relation'] = 'OR';

        if (isset($args['s'])) {
            $post_ids = Loader::getPostIDsByKeywords($args['s']);
            $args['post__in'] = $post_ids;
            unset($args['s']);
        }

        $args['paged'] = $_POST['num'];

        $query = new \WP_Query( $args );

        ob_start();
        while ( $query->have_posts() ):
            $query->the_post();

            $item_data = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
            Loader::renderListItem($item_data,$settings);
        endwhile;

        $html = ob_get_clean();

        if (empty($html) && $settings['advanced_search'] == 'yes' && $settings['search_not_found'])
            $res['posts'] = '<h4 style="margin: 20px auto;">'.$settings['search_not_found'].'</h4>';
        else
            $res['posts'] = $html;

        /* Pagination */
        ob_start();
        Loader::renderListPagination($query, $settings);
        $r = ob_get_clean();

        $res['pagination'] = $r;
        $res['pagination_type'] = $settings['pagi'];
        $res['request'] = $args;

        echo json_encode($res);
        die;
    }

    public static function tyto_find_element_recursive( $elements, $form_id ) {
        foreach ( $elements as $element ) {
            if ( $form_id === $element['id'] ) {
                return $element;
            }

            if ( ! empty( $element['elements'] ) ) {
                $element = Loader::tyto_find_element_recursive( $element['elements'], $form_id );

                if ( $element ) {
                    return $element;
                }
            }
        }

        return false;
    }

    public static function renderListPagination($query, $settings) {
        $total = (int)$query->max_num_pages;
        $current = (int)$_POST['num'] ? (int)$_POST['num'] : 1;
        $r = '';

        if ($settings['pagi'] == 'numbers') {
            /*Set up paginated links.*/
            $end_size = 1;
            $mid_size = 2;
            $page_links = array();
            $dots = false;
            $show_all = false;

            $page_links[] = "<a class='page-numbers hidden' data-num='1'></a>";

            if ($total > 1) {
                if ($current && 1 < $current)
                    $page_links[] = '<a class="prev page-numbers" href="#" data-num="' . ($current - 1) . '"></a>';

                for ($n = 1; $n <= $total; $n++) :
                    if ($n == $current) :
                        $page_links[] = '<a class="current page-numbers" href="#" data-num="' . ($n) . '">' . $n . '</a>';
                        $dots = true;
                    else :
                        if ($show_all || ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) || $n > $total - $end_size)) :
                            $page_links[] = '<a class="page-numbers" href="#" data-num="' . ($n) . '">' . $n . '</a>';
                            $dots = true;
                        elseif ($dots && !$show_all) :
                            $page_links[] = '<span class="page-numbers dots">' . __('&hellip;') . '</span>';
                            $dots = false;
                        endif;
                    endif;
                endfor;
                if ($current && $current < $total) :
                    $page_links[] = '<a class="next page-numbers" href="#" data-num="' . ($current + 1) . '"></a>';
                endif;
            }

            $r .= "<ul class='page-numbers numbers'>\n\t<li>";
            $r .= join( "</li>\n\t<li>", $page_links );
            $r .= "</li>\n</ul>\n";
        } else if ($settings['pagi'] == 'load_more') {
            $r = "<ul class='page-numbers load-more'>\n\t<li><a class='page-numbers hidden' data-num='1'></a>";
            $r .= "";
            if ($current + 1 <= $total) {
                $r .= "<a class='page-numbers' href='#' data-num='".($current + 1)."'>".$settings['pagination_button_text']."</a>";
            }
            $r .= "</li>\n</ul>\n";
        } else if ($settings['pagi'] == 'infinity_scroll') {
            if ($current + 1 <= $total || $current == 1 /*search*/) {
                $r = "<ul class='page-numbers infinity-scroll'>\n\t<li>";
                $r .= "<a class='page-numbers hidden' data-num='1'></a>";
                if ($total > 0) $r .= '<a class="page-numbers" href="#" data-num="'.($current + 1).'">Infinity</a>';
                $r .= "</li>\n</ul>\n";

            }
        }
        echo $r;
    }

    /**
     * @param \Tourware\Model\Travel $item_data
     * @param $settings
     */
    public static function renderListItem($item_data, $settings) {
        $post = get_post(get_the_ID());
        $img_width = $img_height = 1200 / $settings['col'];
        /*VARIABLES*/

        $title = $item_data->getTitle();

        if ($settings['title_length']) {
            if (strlen($title) > $settings['title_length']['size']) {
                $title = substr($title, 0, $settings['title_length']['size']).'...';
            }
        }

        $price = $item_data->getPrice();
        $days = $item_data->getItineraryLength();
        $persons = ($item_data->getPaxMin() ? $item_data->getPaxMin().'-' : '').$item_data->getPaxMax();
        $destination = $item_data->_destination;
        $stars = 0;
        if (get_post_type(get_the_ID()) == 'tytoaccommodations') {
            $stars = $item_data->stars ? $item_data->stars : 1;
        }

        $badge = false;
        if ($settings['show_badge']) {
            if ($item_data->tags) {
                foreach ($item_data->tags as $tag) {
                    if (is_array($settings['badge_tag'])) {
                        foreach ($settings['badge_tag'] as $settings_tag) {
                            if ($tag->name == $settings_tag) {
                                $badge = $settings_tag;
                                break;
                            }
                        }
                    } else {
                        if ($tag->name == $settings['badge_tag']) {
                            $badge = $settings['badge_tag'];
                        }
                    }
                }
            }
        }

        if ($settings['show_excerpt'] ) {
            $excerpt = $post->post_excerpt;
            if (strlen($excerpt) > $settings['excerpt_length']['size']) {
                $excerpt = substr($excerpt, 0, $settings['excerpt_length']['size']).'...';
            }
        }

        if ($settings['show_categories'] && $settings['categories_tags']) {
            $categories = [];
            if ($item_data->tags) {
                foreach ($item_data->tags as $tag) {
                    if (in_array($tag->name, $settings['categories_tags'])) {
                        $categories[] = $tag->name;
                    }
                }
            }
            $categories_str = implode(', ', $categories);
        }

        $img_src = $item_data->getFeaturedImageUri([
            'secure' => true,
            'width' => $img_width,
            'height' => $img_height,
            'crop' => 'thumb'
        ]);


        $parts = explode('##', $settings['template']);

        if ($parts[0] === 'tourware') {
            include Path::getResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
        } else {
            include Path::getChildResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
        }
    }

    public static function getPostIDsByKeywords($search_str) {
        global $wpdb;
        $kw = addcslashes(strtoupper($search_str), '/');

        $ta_args = array(
            'post_type' => ['tytotravels', 'tytoaccommodations'],
            'posts_per_page' => -1,
            'status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'tytodestination',
                    'value' => $kw,
                    'compare' => 'LIKE'
                )
            )
        );
        $t_posts = get_posts($ta_args);
        $by_dest_ids = wp_list_pluck($t_posts, 'ID');

        $by_title_ids = $wpdb->get_col(
            "SELECT ID
                            FROM $wpdb->posts
                            WHERE UCASE( post_title )
                            LIKE '%$kw%'
                            AND post_type IN ('tytotravels', 'tytoaccommodations') 
                            AND post_status='publish'");

        $post_ids = array_unique(array_merge($by_dest_ids, $by_title_ids));
        if (empty($post_ids)) $post_ids = [0];

        return $post_ids;
    }
}