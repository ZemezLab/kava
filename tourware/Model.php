<?php

namespace Tourware;

use \Tourware\Contracts\Model\Displayable;
use \Tourware\Contracts\Model\Imageable;

abstract class Model
{
    /**
     * @var object
     */
    protected $rawData;

    /**
     * @var object
     */
    protected $post;

    /**
     * BaseModel constructor.
     * @param $rawData
     * @param $postId
     */
    public function __construct($rawData, $postId)
    {
        $this->postId = $postId;
        $this->rawData = $rawData;
    }

    public function __get($name)
    {
        return $this->rawData->$name;
    }

    public function __isset($name) {
        return isset($this->rawData->$name);
    }

}