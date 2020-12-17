<?php

namespace Tourware;

class Path
{

    public static function getResourcesFolder()
    {
        return get_parent_theme_file_path() . '/tourware-resources/';
    }

    public static function getChildResourcesFolder()
    {
        return get_theme_file_path() . '/tourware-resources/';
    }

    public static function getResourcesUri()
    {
        return get_parent_theme_file_uri() . '/tourware-resources/';
    }

    public static function getChildResourcesUri()
    {
        return get_theme_file_uri() . '/tourware-resources/';
    }

    public static function getLayoutPath( $template ) {
        $parts = explode('##', $template);

        if ($parts[0] === 'tourware') {
            $layoutPath = Path::getResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
        } else {
            $layoutPath = Path::getChildResourcesFolder() . 'layouts/' . $parts[1] . '/' . $parts[2] . '.php';
        }

        return $layoutPath;
    }
}