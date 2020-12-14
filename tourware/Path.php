<?php

namespace Tourware;

class Path
{

    public static function getResourcesFolder()
    {
        return get_theme_file_path() . '/tourware-resources/';
    }

}