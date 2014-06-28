<?php


namespace ImagineExtra\Filter;


use Imagine\Exception\InvalidArgumentException;
use Imagine\Filter\Advanced\RelativeResize;
use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;

/**
 * Relative resize filter
 *
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class RelativeResizeFilter implements FilterInterface
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function apply(ImageInterface $image)
    {
        if (list($method, $parameter) = each($this->options)) {
            $filter = new RelativeResize($method, $parameter);

            return $filter->apply($image);
        }

        throw new InvalidArgumentException('Expected method/parameter pair, none given');
    }
}