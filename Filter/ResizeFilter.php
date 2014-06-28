<?php


namespace ImagineExtra\Filter;

use Imagine\Filter\Basic\Resize;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Basic resize filter
 *
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class ResizeFilter extends AbstractFilter
{
    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'size' => null,
            ))
            ->setRequired(array('size'))
            ->setAllowedTypes(array(
                'size' => 'array',
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
        list($width, $height) = $this->getOption('size');

        $filter = new Resize(new Box($width, $height));

        return $filter->apply($image);
    }
}