<?php

namespace Tourware\Mixin;

trait HasImages
{

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

        if (!array_key_exists('height', $options)) {
            $options['height'] = $options['width'];
        }

        $data = $this->getRawData();

        if (isset($data->images) && isset($data->images[0])) {
            $firstImageIdentifier = $data->images[0]->image;

            if (strpos($firstImageIdentifier, 'unsplash')) {
                $parts = explode('?', $firstImageIdentifier);
                return $parts[0] . '?fm=jpg&crop=focalpoint&fit=crop&w=' . $options['width'];
            }

            return \Cloudinary::cloudinary_url($firstImageIdentifier, $options);
        } else { // @todo: check if there is a real featured image in the wordpress post
            return 'https://via.placeholder.com/' . $options['width'] . 'x' . $options['height'];
        }
    }

}