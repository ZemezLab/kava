<?php

namespace Tourware;

class RepositoryFactory
{

    /**
     * RepositoryFactory constructor.
     */
    private function __construct()
    {
    }

    private static $postTypeMapping = [
        'tytotravels' => \Tourware\Repository\Travel::class,
        'tytoaccommodations' => \Tourware\Repository\Accommodation::class
    ];

    public static function getRepositoryByPostType($postType)
    {
        if (array_key_exists($postType, self::$postTypeMapping)) {
            return call_user_func(array(self::$postTypeMapping[$postType], 'getInstance'));
        }
    }

}
