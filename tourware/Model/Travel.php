<?php

namespace Tourware\Model;

use Tourware\Contracts\Model\Displayable;
use Tourware\Contracts\Model\Imageable;
use Tourware\Model;

class Travel extends Model implements Displayable, Imageable
{

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

    /**
     * Checks if at least one image exists and can be used as a featured image.
     *
     * @return bool
     */
    public function hasFeaturedImageUri()
    {
        return (isset($this->rawData->images) && isset($this->rawData->images[0]));
    }

    /**
     * Returns specified feature image or the first image coming from tourware.
     *
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function getFeaturedImageUri(array $options = array())
    {
        if (!array_key_exists('width', $options)) {
            throw new \Exception('No image width speficied.');
        }

        if (isset($this->rawData->images) && isset($this->rawData->images[0])) {
            $firstImageIdentifier = $this->rawData->images[0]->image;

            if (strpos($firstImageIdentifier, 'unsplash')) {
                $parts = explode('?', $firstImageIdentifier);
                return $parts[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=' . $options['width'];
            }

            return \Cloudinary::cloudinary_url($firstImageIdentifier, $options);
        }
    }

}