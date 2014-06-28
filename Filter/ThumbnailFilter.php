<?php


namespace ImagineExtra\Filter;

use Imagine\Filter\Basic\Thumbnail;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThumbnailFilter extends AbstractFilter
{
    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'mode' => ImageInterface::THUMBNAIL_OUTBOUND,
                'filter' => ImageInterface::FILTER_UNDEFINED,
                'size' => array(null, null),
                'allow_upscale' => false,
            ))
            ->setAllowedValues(array(
                'mode' => array(ImageInterface::THUMBNAIL_OUTBOUND, ImageInterface::THUMBNAIL_INSET),
                'filter' => array(
                    ImageInterface::FILTER_UNDEFINED,
                    ImageInterface::FILTER_POINT,
                    ImageInterface::FILTER_BOX,
                    ImageInterface::FILTER_TRIANGLE,
                    ImageInterface::FILTER_HERMITE,
                    ImageInterface::FILTER_HANNING,
                    ImageInterface::FILTER_HAMMING,
                    ImageInterface::FILTER_BLACKMAN,
                    ImageInterface::FILTER_GAUSSIAN,
                    ImageInterface::FILTER_QUADRATIC,
                    ImageInterface::FILTER_CUBIC,
                    ImageInterface::FILTER_CATROM,
                    ImageInterface::FILTER_MITCHELL,
                    ImageInterface::FILTER_LANCZOS,
                    ImageInterface::FILTER_BESSEL,
                    ImageInterface::FILTER_SINC
                )
            ))
            ->setRequired(array('mode', 'filter', 'size'))
            ->setAllowedTypes(array(
                'size'=> 'array',
                'allow_upscale' => 'bool',
            ))
            ->setNormalizers(array(
                'size' => Validators::numberPair('size'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function apply(ImageInterface $image)
    {
        $mode = $this->getOption('mode');
        $filter = $this->getOption('filter');

        list($width, $height) = $this->getOption('size');

        $size = $image->getSize();
        $origWidth = $size->getWidth();
        $origHeight = $size->getHeight();

        if (null === $width || null === $height) {
            if (null === $height) {
                $height = (int)(($width / $origWidth) * $origHeight);
            } else if (null === $width) {
                $width = (int)(($height / $origHeight) * $origWidth);
            }
        }

        if (($origWidth > $width || $origHeight > $height)
            || ($this->getOption('allow_upscale') && ($origWidth !== $width || $origHeight !== $height))
        ) {
            $filter = new Thumbnail(new Box($width, $height), $mode, $filter);
            $image = $filter->apply($image);
        }

        return $image;
    }
}