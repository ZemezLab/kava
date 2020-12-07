<?php

require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = lcfirst($file);
    $file = get_theme_file_path() . '/' . $file;

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});

$theme = \Tourware\Theme::getInstance();
$theme->run();