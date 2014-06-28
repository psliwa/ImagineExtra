<?php

namespace ImagineExtra\Filter;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;

/**
 * AutoRotateFilter - rotates an Image based on its EXIF Data
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class AutoRotateFilter implements FilterInterface
{
    /**
     * Applies scheduled transformation to ImageInterface instance
     * Returns processed ImageInterface instance
     *
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function apply(ImageInterface $image)
    {
        if ($orientation = $this->getOrientation($image)) {
            $degree = $this->calculateRotation((int)$orientation);

            if ($degree !== 0) {
                $image->rotate($degree);
            }
        }

        return $image;
    }

    /**
     * calculates to rotation degree from the EXIF Orientation
     *
     * @param  int $orientation
     * @return int
     */
    private function calculateRotation($orientation)
    {
        switch ($orientation) {
            case 8:
                $degree = -90;
                break;
            case 3:
                $degree = 180;
                break;
            case 6:
                $degree = 90;
                break;
            default:
                $degree = 0;
                break;
        }

        return $degree;
    }

    /**
     * @param ImageInterface $image
     * @return int
     */
    private function getOrientation(ImageInterface $image)
    {
        //>0.6 imagine meta data interface
        if (method_exists($image, 'metadata')) {
            return $image->metadata()->offsetGet('exif.Orientation');
        } else {
            $data = exif_read_data("data://image/jpeg;base64," . base64_encode($image->get('jpg')));
            return isset($data['Orientation']) ? $data['Orientation'] : null;
        }
    }
}