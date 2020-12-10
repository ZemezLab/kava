<?php

namespace Tourware\Repository;

use Tourware\Model\Travel as Model;

class Travel
{

    private static $instance = null;
    private static $cache = [];

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function findOneByPostId($postId) {
        if (array_key_exists($postId, self::$cache)) {
            return self::$cache[$postId];
        }

        $data = json_decode(get_post_meta($postId, 'tytorawdata', true));
        $record = new Model($data, $postId);

        self::$cache[$postId] = $record;

        return self::$cache[$postId];
    }

}