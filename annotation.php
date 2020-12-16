<?php

$version = $argv[1];
$regex = '/Version:\s{1}\d.\d.\d/';
$replacement = 'Version: ' . $version;

$main = file_get_contents('style.css');
$main = preg_replace($regex, $replacement, $main);

file_put_contents('style.css', $main);