<?php


namespace ImagineExtra\Filter;

use Imagine\Filter\Basic\Crop;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CropFilter extends AbstractFilter
{
    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'start' => null,
            'size' => null,
        ));

        $resolver->setAllowedTypes(array(
            'start' => 'array',
            'size' => 'array',
        ));
        $resolver->setRequired(array('start', 'size'));
        $resolver->setNormalizers(array(
            'size' => Validators::numberPair('size'),
            'start' => Validators::numberPair('start'),
        ));
    }

    public function apply(ImageInterface $image)
    {
        list($x, $y) = $this->getOption('start');
        list($width, $height) = $this->getOption('size');

        $filter = new Crop(new Point($x, $y), new Box($width, $height));
        return $filter->apply($image);
    }
}