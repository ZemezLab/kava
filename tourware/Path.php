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

}