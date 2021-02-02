<?php

namespace Tourware\Elementor\DynamicTag;

use Tourware\Repository\Repository;

class Travel extends \Elementor\Core\DynamicTags\Tag {

    /**
     * Get Name
     *
     * Returns the Name of the tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_name() {
        return 'travel';
    }

    /**
     * Get Title
     *
     * Returns the title of the Tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_title() {
        return __( 'Reise', 'elementor-pro' );
    }

    /**
     * Get Group
     *
     * Returns the Group of the tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_group() {
        return 'tourware';
    }

    /**
     * Get Categories
     *
     * Returns an array of tag categories
     *
     * @since 2.0.0
     * @access public
     *
     * @return array
     */
    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY
        ];
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function _register_controls() {

        $variables = [
            'highlights' => 'Highlights',
            'subtitle' => 'Untertitel',
            'id' => 'Record ID',
        ];

        $this->add_control(
            'param_name',
            [
                'label' => __( 'Parameter', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $variables,
            ]
        );
    }

    /**
     * Render
     *
     * Prints out the value of the Dynamic tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return void
     */
    public function render() {
        $parameter = $this->get_settings( 'param_name' );
        $repository = \Tourware\Repository\Travel::getInstance();
        $record = $repository->findOneByPostId(get_the_ID());
        $display = '';

        try {
            $display =  $record->$parameter;
        } catch (\Exception $e) {}

        echo wp_kses_post( $display );
    }
}
