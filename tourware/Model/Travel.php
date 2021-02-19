<?php

namespace Tourware\Model;

use Tourware\Contract\Model\Displayable;
use Tourware\Contract\Model\Imageable;
use Tourware\Mixin\HasImages;
use Tourware\Model;

/**
 * Class Travel
 * @package Tourware\Model
 */
class Travel extends Model implements Displayable, Imageable
{
    use HasImages;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getRawProperty('title');
    }

    /**
     * @return string
     */
    public function getTeaser()
    {
        return substr($this->getRawProperty('description'),0, 250);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getRawProperty('type');
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return floatval($this->getRawProperty('price'));
    }

    /**
     * @return int
     */
    public function getPaxMin()
    {
        return intval($this->getRawProperty('paxMin'));
    }

    /**
     * @return int
     */
    public function getPaxMax()
    {
        return intval($this->getRawProperty('paxMax'));
    }

    /**
     * @return array
     */
    public function getItinerary()
    {
        $itinerary = $this->getRawProperty('itinerary');
        return $itinerary ? $itinerary : array();
    }

    /**
     * @return array
     */
    public function getDates()
    {
        $dates = [];
        if ('INDEPENDENT' == $this->getType()) {
            $dates[0]->start = $this->getRawProperty('travelBegin');
            $dates[0]->end = $this->getRawProperty('travelEnd');
        } else {
            $dates = $this->getRawProperty('dates');
        }
        return $dates;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $tags = $this->getRawProperty('tags');
        return $tags ? $tags : array();
    }

    /**
     * @return array
     */
    public function getHighlights()
    {
        $highlights = $this->getRawProperty('highlights');
        return $highlights ? $highlights : '';
    }

    /**
     * @return int
     */
    public function getItineraryLength()
    {
        $itinerary = $this->getItinerary();
        return $itinerary ? array_sum(array_column($itinerary, 'days')) : 0;
    }

    /**
     * @param $field
     * @return string
     */
    public function getAdditionalField($field)
    {
        $additionalFields = $this->getRawProperty('additionalFields');
        return $additionalFields && !empty($additionalFields->$field) ? $additionalFields->$field : '';
    }

    /**
     * @return array
     */
    public function getResponsibleUser() {
        return $this->getRawProperty('responsibleUser');
    }

    /**
     * @return string
     */
    public function getKmlFile() {
        $kml = $this->getRawProperty('kmlFile');
        return $kml ? $kml : '';
    }

    /**
     * @return array
     */
    public function getCountries() {
        return $this->getRawProperty('countries');
    }

    /**
     * @return array
     */
    public function getAccommodations() {
        $accommodations = []; $prev = null; $nights = 0; $i = 0;
        $itinerary = $this->getItinerary();
        usort($itinerary, function($a, $b) {
            return $a->position <=> $b->position;
        });

        foreach ($itinerary as $item) {
            if (!empty($item->accommodations)) {
                foreach ($item->accommodations as $item_accommodation) {
                    if (!empty($item_accommodation->travel)) {
                        $accommodation = $item_accommodation;
                        $acc_id = $item_accommodation->accommodation->id;
                        if (!empty($accommodation) && !empty($acc_id)) {
                            if ($prev == null && $i == 0) {
                                array_push($accommodations, $accommodation);
                                $i = 1;
                            }
                            if ($prev !== null && $prev != $acc_id) {
                                if ($i > 0) $accommodations[$i-1]->nights = $nights;
                                array_push($accommodations, $accommodation);
                                $nights = 0;
                                $i++;
                            }
                            if (!empty($accommodation)) $nights += intval($accommodation->nights);
                            $prev = $acc_id;
                        }
                    }
                }
            }
        }

        if ($nights !== 0) {
            $last = count($accommodations) - 1;
            $accommodations[$last]->nights = $nights;
        }

        return $accommodations;
    }

}