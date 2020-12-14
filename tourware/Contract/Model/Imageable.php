<?php

namespace Tourware\Contract\Model;

/**
 * Interface Imageable
 * @package Tourware\Contract\Model
 */
interface Imageable
{

    /**
     * Checks if at least one image exists and can be used as a featured image.
     *
     * @return bool
     */
    public function hasFeaturedImageUri();

    /**
     * Returns specified feature image or the first image coming from tourware.
     *
     * @return string
     */
    public function getFeaturedImageUri();

}