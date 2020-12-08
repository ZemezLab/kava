<?php

namespace Tourware\Model;

use \Tourware\Contracts\Model\Displayable;
use \Tourware\Contracts\Model\Imageable;

abstract class BaseModel implements Displayable, Imageable
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
     */
    public function __construct($postId, $rawData)
    {
        $this->postId = $postId;
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->rawData->title;
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return substr($this->rawData->description,0, 250);
    }

    public function getFeaturedImageUri()
    {
        if (isset($this->rawData->images) && isset($this->rawData->images[0])) {
            return $this->rawData->images[0]->image;
        }
    }

}