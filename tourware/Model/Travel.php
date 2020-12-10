<?php

namespace Tourware\Model;

use Tourware\Contracts\Model\Displayable;
use Tourware\Contracts\Model\Imageable;
use Tourware\Traits\HasImages;
use Tourware\Model;

class Travel extends Model implements Displayable, Imageable
{
    use HasImages;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getRawData()->title;
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return substr($this->rawData->description,0, 250);
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->getRawData()->price;
    }

    /**
     * @return int
     */
    public function getPaxMin()
    {
        return intval($this->getRawData()->paxMin);
    }

    /**
     * @return int
     */
    public function getPaxMax()
    {
        return intval($this->getRawData()->paxMax);
    }

    /**
     * @return array
     */
    public function getItinerary()
    {
        return $this->getRawData()->itinerary ? $this->getRawData()->itinerary : array();
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return $this->getRawData()->dates ? $this->getRawData()->dates : array();
    }

    /**
     * @return int
     */
    public function getItineraryLength()
    {
        $itinerary = $this->getItinerary();
        return $itinerary ? array_sum(array_column($itinerary, 'days')) : 0;
    }

}