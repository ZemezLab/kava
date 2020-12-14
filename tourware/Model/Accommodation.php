<?php

namespace Tourware\Model;

use Tourware\Contract\Model\Displayable;
use Tourware\Contract\Model\Imageable;
use Tourware\Mixin\HasImages;
use Tourware\Model;

/**
 * Class Accommodation
 * @package Tourware\Model
 */
class Accommodation extends Model implements Displayable, Imageable
{
    use HasImages;

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

}