<?php

namespace Tourware;

abstract class Customizer
{

    protected $options;

    /**
     * Register new styles and options
     */
    public function register($options)
    {
        $this->options = $options;
        $this->additionalOptions = $this->getAdditionalOptions($this->options);
        $this->updateThemeOptions($this->additionalOptions);
        $this->options['options'] = array_merge( $this->additionalOptions, $this->options['options'] );

        return $this->options;
    }

    protected function getAdditionalOptions($options)
    {
        return array();
    }

    /**
     * @param $options
     */
    protected function updateThemeOptions($options)
    {
        $mods = get_theme_mods();

        foreach ( $options as $id => $option ) {
            if ( 'control' != $option['type'] ) {
                continue;
            }

            if ( isset( $mods[ $id ] ) ) {
                continue;
            }

            $mods[ $id ] = $this->getDefaultOptionValue( $options, $id );
        }
        $theme = get_option( 'stylesheet' );
        update_option( "theme_mods_$theme", $mods );
    }

    /**
     * @param $options
     * @param $id
     * @return mixed|null
     */
    protected function getDefaultOptionValue($options, $id)
    {
        return isset( $options[ $id ]['default'] ) ? $options[ $id ]['default'] : null;
    }

}