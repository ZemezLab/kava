<?php

namespace Tourware\Model;

use Tourware\Contracts\Model\Displayable;
use Tourware\Contracts\Model\Imageable;
use Tourware\Traits\HasImages;
use Tourware\Model;

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